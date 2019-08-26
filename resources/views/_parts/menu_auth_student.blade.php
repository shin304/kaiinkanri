
<div id="section_content1">
    <input type="hidden" name="pschool_id" value="{{request('id')}}">
        
    <table style="width:100%;">
        <colgroup>
            <col style="width: 45%">
            <col style="width: 10%">
            <col style="width: 45%">
        </colgroup>
        <tr>
            <th colspan=""><h3>{{$lan::get('menu_structure')}}</h3></th>
        </tr>

        <tr>
            <!-- FULL MENU LIST  -->
            <td>
                <ul id="reference-menu" class="ul-column-title">
                    @foreach(old('parentMenuList', request('parentMenuList')) as $idx=>$pmenu)
                        <li idx="{{$pmenu['master_menu_id']}}" class="menu-refer-row menu-{{$pmenu['menu_name_key']}}" editable="{{$pmenu['master_editable']}}">
                            {{$lan::get($pmenu['menu_name_key'])}}

                            <!-- SUBMENU LIST -->
                            @if(array_key_exists($pmenu['master_menu_id'], old('parentSubMenuList', request('parentSubMenuList'))))
                            <ul id="sub-reference-menu" >
                            @foreach(old('parentSubMenuList', request('parentSubMenuList'))[$pmenu['master_menu_id']] as $smenu)
                                <li idx="{{$smenu['master_menu_id']}}" editable="{{$smenu['master_editable']}}">{{$lan::get($smenu['menu_name_key'])}}</li>
                            @endforeach
                            </ul>
                        @endif
                        </li>
                    @endforeach
                </ul>
            </td>
            <td style="vertical-align: middle">
                <div><button type="button" class="btn-move-col" id="move_to_filter"><i style="font-size:12px;" class="fa fa-angle-double-right"></i></button><br>
                    <button type="button" class="btn-move-col" id="revert_to_refer"><i style="font-size:12px;" class="fa  fa-angle-double-left"></i></button></div>
            </td>

            <!-- FILTER MENU TABLE  -->
            <td>
                <ul id="filter-menu" class="ul-column-title" style="min-height: 200px;">
                    <!-- list exist list menu -->
                    @if (count(old('defaultMenuList', request('defaultMenuList'))) > 0)
                    @foreach(old('defaultMenuList', request('defaultMenuList')) as $dmenu)

                        <li idx="{{$dmenu['master_menu_id']}}"  class="filter-row menu-{{$dmenu['menu_name_key']}}" >{{$lan::get($dmenu['menu_name_key'])}}
                            <!--  create delete icon -->
                            @if (!$dmenu['default_flag'])
                            <a href="" title="Delete " class="delete-menu ui-icon ui-icon-close" style="float: right;"></a>
                            @endif
                            <!--  create edit button -->
                            @if (array_get($dmenu, 'master_editable') == 1)
                                @if (!$dmenu['default_flag'])
                                    <button type="button" class="editable editable-{{$dmenu['master_menu_id']}} btn_auth" title="編集">
                                        <input type="checkbox" name="editable_list[{{$dmenu['master_menu_id']}}]" value="{{$dmenu['master_menu_id']}}" @if (array_get($dmenu, 'editable')) checked @endif>
                                        <i class="fa @if (array_get($dmenu, 'editable')) fa-check @else fa-close @endif"></i><i class="fa fa-edit"></i>
                                    </button>
                                @else
                                    <span class="btn_auth non_editable"><input type="checkbox" name="editable_list[{{$dmenu['master_menu_id']}}]" value="{{$dmenu['master_menu_id']}}" @if (array_get($dmenu, 'editable')) checked @endif>
                                    </span>
                                @endif

                            @endif
                            <!--  create view button -->
                            <span class="btn_auth non_editable"></span>

                            <!--  create menu_list[] -->
                            <input type="hidden" class="menu_item" value="{{$dmenu['master_menu_id']}}" checked="" name="menu_list[{{$dmenu['master_menu_id']}}]">
                            <!-- SUBMENU LIST -->
                            @if(array_key_exists($dmenu['master_menu_id'], old('defaultSubMenuList', request('defaultSubMenuList')) ))
                                <ul id="sub-reference-menu" >
                                    @foreach(old('defaultSubMenuList', request('defaultSubMenuList'))[$dmenu['master_menu_id']] as $smenu)
                                        <li idx="{{$smenu['master_menu_id']}}" >{{$lan::get($smenu['menu_name_key'])}}
                                        <!--  create delete icon -->
                                        <a href="" title="Delete " class="delete-menu ui-icon ui-icon-close" style="float: right;"></a>

                                        <!--  create edit button -->
                                        @if (array_get($dmenu, 'master_editable') == 1) <!--  depend on parent => $dmenu[master_editable]-->
                                        <button type="button" class="editable editable-{{$smenu['master_menu_id']}} btn_auth" title="編集">
                                            <input type="checkbox" name="editable_list[{{$smenu['master_menu_id']}}]" value="{{$smenu['master_menu_id']}}" @if (array_get($smenu, 'editable')) checked @endif>
                                            <i class="fa @if (array_get($smenu, 'editable')) fa-check @else fa-close @endif"></i><i class="fa fa-edit"></i>
                                        </button>
                                        @endif

                                        <!--  create menu_list[] -->
                                        <input type="hidden" class="menu_item" value="{{$smenu['master_menu_id']}}" checked="" name="menu_list[{{$smenu['master_menu_id']}}]">
                                    </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    @endif
                </ul>
            </td>
        </tr>
    </table>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="/css{{$_app_path}}menu_setting.css" />
<style rel="stylesheet">
.filter_view {
    background: #ff8c00 !important;
    color: #fff;
}
.filter_view label{
    color: inherit;
}
</style>
<script type="text/javascript">
    $(function() {
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

    });

    /**
     * Load Edit, View checkbox. Add delete icon
     * @param ui
     */
    function loadEditAuth(ui) {
//        var old_index = $(ui.item).data('old-idx');
        var new_index = (ui.item.attr("idx"));
        var editable  = (ui.item.attr("editable"));
//        todo delete if exist
        if ($('#filter-menu li[idx=' + new_index+']').length > 1) {

            $(ui.item).remove();
            $('#filter-menu li').css({width:'', height:''});
            $('.menu-row').removeClass('filter_view');
            return;
        }

//        todo append delete icon
        ui.item.parent().find( 'li').each( function( idx, el ) {
            var $this = $( el ), idx = $( el ).attr('idx');

            if (!$this.has('a.delete-menu').length && !$this.has('span.non_editable').length) {
//                append Input menu_list[x], delete icon, view checkbox, edit checkbox
                appendItem($this, idx, editable);
            }
        });

//      todo clear class filter_view
        $('#filter-menu li').css({width:'', height:''});
        $(ui.item).removeClass('filter_view');
        $('.menu-row').removeClass('filter_view');
    }

    /**
     * append Input menu_list[x], delete icon
     * @param obj
     */
    function appendItem(obj, idx, editable) {
//        append menu_list[x]
        obj.append( '<input type="hidden" class="menu_item" value="'+idx+'" checked="" name="menu_list['+idx+']">' );

//        append view, edit checkbox
            /*obj.prepend( '<button type="button" class="viewable viewable-'+idx+' btn_auth" title="表示"><input type="checkbox" name="viewable_list['+idx+']" value="'+idx+'" checked><i class="fa fa-check"></i><i class="fa fa-eye"></i></button>' );*/
        if (editable==1) {
            obj.prepend( '<button type="button" class="editable editable-'+idx+' btn_auth" title="編集"><input type="checkbox" name="editable_list['+idx+']" value="'+idx+'" checked><i class="fa fa-check"></i><i class="fa fa-edit"></i></button>' );
        }
//        append delete icon
        obj.prepend( '<a href="" title="Delete " class="delete-menu ui-icon ui-icon-close" style="float: right;"></a>' );
    }

    function selectItem($item) {
        $item.prop('checked', true).next().removeClass('fa-close').addClass('fa-check');

    }
    function unSelectItem($item) {
        $item.attr('checked', false).next().removeClass('fa-check').addClass('fa-close');
    }
</script>
