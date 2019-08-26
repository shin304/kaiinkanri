<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="/css{{$_app_path}}menu_setting.css" />
<script type="text/javascript">
    $(function() {
        // set list sequence
        // seq_lst: [1,2,3,7,...] #master_menu_id
        var seq_lst=[];
        @foreach ( $parentMenuList as $index => $menu)
seq_lst.push({{$menu['master_menu_id']}});
        @if (array_key_exists($menu['master_menu_id'],$parentSubMenuList))
        @foreach ($parentSubMenuList[$menu['master_menu_id']] as $idx => $submenu)
            seq_lst.push({{$submenu['master_menu_id']}});
        @endforeach
        @endif
    @endforeach

    $('.menu_item').change(function() {
            //Ex: li: <li idx="1"><input type="checkbox" class="menu_item"...</li>
            var li = $(this).parent().clone();

            var idx = ($(this).parent().attr('idx'));
            // hide checkbox, define name, remove attribute disable if exist
            li.children().first().attr('name', 'menu_list['+idx+']');
            li.children().first().attr('type', 'hidden');
            li.children().first().attr('disabled', false);

            if ($(this).is(":checked")) {
                // append for the first time OR top list
                if ($('#menu_side li').length == 0 || idx == 0) {
                    $('#menu_side').prepend(li);
                } else {

                    //append to next position
                    var nodeIndex = seq_lst.indexOf(parseInt(idx)); flag = false;
                    $.each(seq_lst.slice( 0,nodeIndex ).reverse(), function(i, val){
                        objPre = '#menu_side li[idx='+val+']';
                        if ($(objPre).length != 0) {

                            $(li).insertAfter(objPre);
                            flag=true;return false;
                        }
                    });

                    // case cannot find next position, then append to top
                    if (!flag) {
                        $('#menu_side').prepend(li);
                    }
                }
                //display view-edit list
                $('#view_edit li[idx='+idx+']').addClass('displayLi').removeClass('hideLi');
                $('#view_edit li[idx='+idx+'] input[class=chkViewable]').attr('name', 'viewable_list['+idx+']');// allow pass to server
                $('#view_edit li[idx='+idx+'] input[class=chkEditable]').attr('name', 'editable_list['+idx+']');// allow pass to server

                // $('#view_edit li[idx='+idx+'] select').attr('disabled', false);// allow pass to server
                //display up-down button
                $('#up_down li[idx='+idx+']').addClass('displayLi').removeClass('hideLi');

                //reload position
                var idxprev = li.prev().attr('idx');
                if (idxprev != undefined) {
                    $('#view_edit li[idx='+idx+']').insertAfter($('#view_edit li[idx='+idxprev+']'));
                    $('#up_down li[idx='+idx+']').insertAfter($('#up_down li[idx='+idxprev+']'));
                }
                //unselect menu
            } else {
                $('#menu_side li[idx='+idx+']').remove();
                //remove view-edit list
                $('#view_edit li[idx='+idx+']').addClass('hideLi').removeClass('displayLi');
                $('#view_edit li[idx='+idx+'] input[class=chkViewable]').attr('name', '');// prevent pass to server
                $('#view_edit li[idx='+idx+'] input[class=chkEditable]').attr('name', '');// prevent pass to server
                // $('#view_edit li[idx='+idx+'] select').attr('disabled', true); // prevent pass to server
                //remove up-down button
                $('#up_down li[idx='+idx+']').addClass('hideLi').removeClass('displayLi');
            }

            //review list to hide first and last button
            reviewUpDown();

            //constraint logic select menu-submenu
            // check submenu => check parent menu
            if (li.hasClass('submenu')){
                if ($(this).is(":checked")) {
                    $('#master_menu_side li[idx='+idx+']').prevAll(":not(.submenu)").children().each(function(i, value){
                        if (!$(value).is(":checked")) {
                            $(value).prop('checked',true);
                            $(value).change();
                        }
                        return false;
                    });
                }
                // uncheck menu => uncheck submenu
            } else {
                if (!$(this).is(":checked")) {
                    $('#master_menu_side li[idx='+idx+']').nextUntil(":not(.submenu)").children(':checked').each(function(i, value){
                        $(value).attr('checked',false);
                        $(value).change();
                    });
                }
            }
        });

        @foreach ( $parentMenuList as $index => $menu)
            @if ($menu['default_flag'] == 1)
setDefaultFlag({{$menu["master_menu_id"]}});
        @endif
        @endforeach

        //applay default_menu/template
                @foreach ( $defaultMenuList as $index => $menu)
        var master_menu_id = {{$menu['master_menu_id']}};
        if (!$('.menu_item[value='+master_menu_id+']').is(":checked")) {
            $('.menu_item[value='+master_menu_id+']').attr('checked', true);
            $('.menu_item[value='+master_menu_id+']').change();
            @if ($menu['viewable'] == 0)
                $('#view_edit li[idx='+master_menu_id+'] input[class=chkViewable]').attr('checked', false);
            @endif
            @if ($menu['editable'] == 0)
                $('#view_edit li[idx='+master_menu_id+'] input[class=chkEditable]').attr('checked', false);
            @endif
        }
        @endforeach

        //constraint logic select view-edit
        $('.chkViewable, .chkEditable').change(function() {
            if ($(this).is(":checked")) {
                //check edit => check view
                if ($(this).hasClass('chkEditable')) {$(this).prev().prop('checked',true);}

            } else {
                //uncheck view => uncheck edit
                if ($(this).hasClass('chkViewable')) {$(this).next().attr('checked',false);}
            }
        });

        // allow pass disable field view, edit to serve
        $('.submit').click(function() {
            $('#view_edit input').attr('disabled', false);
            return true;
        });

        loadPositionBySeqNo(seq_lst);
    });

    function loadPositionBySeqNo(seq_lst) {
        var prev_id;
        var index;
        var prev_index;

                @foreach ( $defaultMenuList as $index => $menu)
        var master_menu_id = {{$menu['master_menu_id']}};
        // sort by seq_no
        @if (!$loop->first)

            index = seq_lst.indexOf(master_menu_id);
        prev_index = seq_lst.indexOf(prev_id);
        if (index < prev_index) {
            stepDown(master_menu_id, prev_id);
        }

        // stepDown(master_menu_id);
        @endif
            prev_id = master_menu_id;
        @endforeach
    }
    // master menu assigned default
    function setDefaultFlag(id){
        $('.menu_item[value='+id+']').change();
        $('#view_edit li[idx='+id+'] input[class=chkViewable]').attr('disabled', true);
        $('#view_edit li[idx='+id+'] input[class=chkEditable]').attr('disabled', true);
    }
    function stepDown(idx, next_id) {
        var objMenu = $('#menu_side li[idx='+idx+']');
        var objNext = $('#menu_side li[idx='+idx+']').nextAll(":not(.submenu)").first();

        if (next_id) {
            objNext = $('#menu_side li[idx='+next_id+']');
        }
        //append objNext has submenu
        if (objNext.nextUntil(":not(.submenu)").last().length > 0) {
            objNext = objNext.nextUntil(":not(.submenu)").last();
        }
        var idnext  = objNext.attr('idx');

        //append objMenu has submenu
        $(objMenu.nextUntil(":not(.submenu)").get().reverse()).each(function (i, value) {
            subidx = $(value).attr('idx');

            //append submenu to next li
            $(value).insertAfter(objNext);
            $('ul#view_edit li[idx='+subidx+']').insertAfter($('ul#view_edit li[idx='+idnext+']'));
            $('ul#up_down li[idx='+subidx+']').insertAfter($('ul#up_down li[idx='+idnext+']'));
        });

        objMenu.insertAfter(objNext);
        $('ul#view_edit li[idx='+idx+']').insertAfter($('ul#view_edit li[idx='+idnext+']'));
        $('ul#up_down li[idx='+idx+']').insertAfter($('ul#up_down li[idx='+idnext+']'));

        //review list to hide first and last button
        reviewUpDown();
    }
    function stepDownSub(idx) {
        var objMenu = $('#menu_side li[idx='+idx+']');
        var objEdit = $('#view_edit li[idx='+idx+']');
        var objUpDown = $('#up_down li[idx='+idx+']');
        objMenu.insertAfter(objMenu.next(".submenu"));
        objEdit.insertAfter(objEdit.next(".submenu.displayLi"));
        objUpDown.insertAfter(objUpDown.next(".submenu.displayLi"));
        reviewUpDown();
    }
    function stepUp(idx) {
        var objMenu = $('#menu_side li[idx='+idx+']');
        var objPrev = $('#menu_side li[idx='+idx+']').prevAll(":not(.submenu)").first();
        var idprev  = objPrev.attr('idx');
        var subidx  ;
        //append objMenu has submenu
        $(objMenu.nextUntil(":not(.submenu)").get().reverse()).each(function (i, value) {
            subidx = $(value).attr('idx');

            //append submenu to prev li
            $(value).insertBefore($('ul#menu_side li[idx='+idprev+']'));
            $('ul#view_edit li[idx='+subidx+']').insertBefore($('ul#view_edit li[idx='+idprev+']'));
            $('ul#up_down li[idx='+subidx+']').insertBefore($('ul#up_down li[idx='+idprev+']'));
            idprev = subidx;
        });

        objMenu.insertBefore($('ul#menu_side li[idx='+idprev+']'));
        $('ul#view_edit li[idx='+idx+']').insertBefore($('ul#view_edit li[idx='+idprev+']'));
        $('ul#up_down li[idx='+idx+']').insertBefore($('ul#up_down li[idx='+idprev+']'));

        //review list to hide first and last button
        reviewUpDown();
    }
    function stepUpSub(idx) {
        var objMenu = $('#menu_side li[idx='+idx+']');
        var objEdit = $('#view_edit li[idx='+idx+']');
        var objUpDown = $('#up_down li[idx='+idx+']');
        objMenu.insertBefore(objMenu.prev(".submenu"));
        objEdit.insertBefore(objEdit.prevAll(".submenu.displayLi").first());
        objUpDown.insertBefore(objUpDown.prevAll(".submenu.displayLi").first());
        reviewUpDown();
    }
    function reviewUpDown() {
        //reverse all display
        $('#up_down li[class=displayLi] button').css('display', 'inline-block');

        //check first and last button
        $('#menu_side li:not(.submenu)').each(function(index) {
            var idx = $(this).attr('idx');
            // first element
            if (index == 0) {
                $('#up_down li[idx='+idx+'] #btnUp').hide();
                //last element
            } else if (index == $('#menu_side li:not(.submenu)').length-1) {
                $('#up_down li[idx='+idx+'] #btnDown').hide();
            } else {
                $('#up_down li[idx='+idx+'] #btnUp').show();
                $('#up_down li[idx='+idx+'] #btnDown').show();
            }

        });

        //submenu
        $('#up_down .submenu button, #up_downli.displayLi button').css('display', 'inline-block');
        $('#menu_side li.submenu').each(function(index) {
            var idx = $(this).attr('idx');
            // first element
            if (index == 0) {
                $('#up_down li[idx='+idx+'] #btnUp').hide();
                //last element
            } else if (index == $('#menu_side li.submenu').length-1) {
                $('#up_down li[idx='+idx+'] #btnDown').hide();
            } else {
                $('#up_down li[idx='+idx+'] #btnUp').show();
                $('#up_down li[idx='+idx+'] #btnDown').show();
            }

        });
    }

</script>
<script type="text/javascript">

</script>
    <form action="/school/menu/input" method="post" name="form1" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="pschool_id" value="{{request('id')}}">
        <table id="menu_assign_tbl">
            <tr>
                <th><h3>{{$lan::get('menu_structure')}}</h3></th>
                <th><h3>{{$lan::get('preview')}}</h3></th>
            </tr>
            <tr>
                <td></td>
                <td>
                    <ul id="preview_head_title">
                        <li></li>
                        <li>表示・編集</li>
                        <li>順番</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <ul id="master_menu_side">
                        @foreach ($parentMenuList as $index => $menu)
                            <li idx="{{$menu['master_menu_id']}}">
                                <input type="checkbox" class="menu_item" id="menu{{$menu['master_menu_id']}}" value="{{$menu['master_menu_id']}}"
                                       @if ($menu['default_flag'] == 1) checked disabled @endif>
                                <label for="menu{{$menu['master_menu_id']}}">{{$lan::get($menu['menu_name_key'])}}</label>
                            </li>
                            <!-- display sub menu -->
                            @if (array_key_exists($menu['master_menu_id'],$parentSubMenuList))
                                @foreach ($parentSubMenuList[$menu['master_menu_id']] as $idx => $submenu)
                                    <li idx="{{$submenu['master_menu_id']}}" class="submenu">
                                        <input type="checkbox" class="menu_item" id="menu{{$submenu['master_menu_id']}}"  value="{{$submenu['master_menu_id']}}"><label for="menu{{$submenu['master_menu_id']}}">{{$lan::get($submenu['menu_name_key'])}}</label>
                                    </li>
                                @endforeach
                            @endif

                        @endforeach
                    </ul>
                </td>
                <td class="block_preview">
                    <ul id="menu_side" >

                    </ul>

                    <ul id="view_edit">
                        @foreach ($parentMenuList as $index => $menu)
                            <li idx="{{$menu['master_menu_id']}}" class="hideLi">
                                <input type="checkbox" class="chkViewable" checked="checked">
                                <input type="checkbox" class="chkEditable" @if ($menu['master_editable'] != 1) disabled="disabled" @else checked="checked" @endif>
                            </li>
                            <!-- Submenu -->
                            @if (array_key_exists($menu['master_menu_id'],$parentSubMenuList))
                                @foreach ($parentSubMenuList[$menu['master_menu_id']] as $idx => $submenu)
                                    <li idx="{{$submenu['master_menu_id']}}" class="hideLi submenu">
                                        <input type="checkbox" class="chkViewable" checked="checked">
                                        <input type="checkbox" class="chkEditable" @if ($submenu['master_editable'] != 1) disabled="disabled" @else checked="checked" @endif>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>

                    <ul id="up_down">
                        @foreach ($parentMenuList as $index => $menu)
                            <li idx="{{$menu['master_menu_id']}}" class="hideLi">
                                <button type="button" id="btnDown" onclick="stepDown({{$menu['master_menu_id']}})"><i class="fa fa-caret-down"></i></button>
                                <button type="button" id="btnUp" onclick="stepUp({{$menu['master_menu_id']}})"><i class="fa fa-caret-up"></i></button>
                            </li>
                            <!-- Submenu -->
                            @if (array_key_exists($menu['master_menu_id'],$parentSubMenuList))
                                @foreach ($parentSubMenuList[$menu['master_menu_id']] as $idx => $submenu)
                                    <li idx="{{$submenu['master_menu_id']}}" class="hideLi submenu">
                                        <button type="button" id="btnDown" onclick="stepDownSub({{$submenu['master_menu_id']}})"><i class="fa fa-caret-down"></i></button>
                                        <button type="button" id="btnUp" onclick="stepUpSub({{$submenu['master_menu_id']}})"><i class="fa fa-caret-up"></i></button>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </td>

            </tr>
        </table>
            <!-- </table><input type="button" name="btn" class="submit" value="保存"> -->
    </form>

