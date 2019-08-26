
<link type="text/css" rel="stylesheet" href="/css/admin/menu_custom.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<div class="page-menu-assign">
    <h4 >メニュー設定</h4>
    <div id="menu_left">
        <ul id="master_menu_side">
            @foreach ($masterMenuList as $index => $menu)
                <li idx="{{$index}}">
                    <input type="checkbox" class="menu_item" value="{{$index}}" id="master{{$index}}" @if ($menu['default_flag'] == 1 || (isset($request['menu_list']) && in_array($index,$request['menu_list'])))
                    checked="checked" @endif @if ($menu['default_flag'] == 1) disabled="disabled" @endif>
                    <label for="master{{$index}}">{{$lan::get($menu['menu_name_key'])}}</label>
                </li>
                <!-- display sub menu -->
                @if (array_key_exists($index,$subMenuList))
                    @foreach ($subMenuList[$index] as $idx => $submenu)
                        <li idx="{{$idx}}" class="submenu">
                            <input type="checkbox" class="menu_item" value="{{$idx}}" id="master{{$idx}}"><label for="master{{$idx}}" @if ($submenu['default_flag'] == 1)  disabled="disabled"
                                                                                                                 checked="checked" @endif >{{$lan::get($submenu['menu_name_key'])}}</label>
                        </li>
                    @endforeach
                @endif

            @endforeach
        </ul>
    </div>

    <div id="menu_right">
        <span>表示・編集 順番</span>
        <ul id="menu_side" >

        </ul>

        <ul id="view_edit">
            @foreach ($masterMenuList as $index => $menu)
                <li idx="{{$index}}" class="hideLi">
                    <input type="checkbox" class="chkViewable" checked="checked" @if ($menu['default_flag'] == 1)
                    disabled="disabled" @endif>
                    <input type="checkbox" class="chkEditable" @if ($menu['editable'] != 1) disabled="disabled" @else checked="checked" @endif
                    @if ($menu['default_flag'] == 1) disabled="disabled" @endif>
                </li>
                <!-- Submenu -->
                @if (array_key_exists($index,$subMenuList))
                    @foreach ($subMenuList[$index] as $idx => $submenu)
                        <li idx="{{$idx}}" class="hideLi submenu">
                            <input type="checkbox" class="chkViewable" checked="checked" @if ($submenu['default_flag'] == 1) disabled="disabled" @endif>
                            <input type="checkbox" class="chkEditable" @if ($submenu['editable'] != 1) disabled="disabled" @else checked="checked" @endif
                            @if ($submenu['default_flag'] == 1) disabled="disabled" @endif>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>

        <ul id="up_down">
            @foreach ($masterMenuList as $index => $menu)
                <li idx="{{$index}}" class="hideLi">
                    <button type="button" id="btnDown" onclick="stepDown({{$index}})"><i class="fa fa-caret-down"></i></button>
                    <button type="button" id="btnUp" onclick="stepUp({{$index}})"><i class="fa fa-caret-up"></i></button>
                </li>
                <!-- Submenu -->
                @if (array_key_exists($index,$subMenuList))
                    @foreach ($subMenuList[$index] as $idx => $submenu)
                        <li idx="{{$idx}}" class="hideLi submenu">
                            <button type="button" id="btnDown" onclick="stepDownSub({{$idx}})"><i class="fa fa-caret-down"></i></button>
                            <button type="button" id="btnUp" onclick="stepUpSub({{$idx}})"><i class="fa fa-caret-up"></i></button>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
<script>
    // js for menu setting
    var seq_lst=[];
    $('#master_menu_side li').each(function() {
        seq_lst.push(parseInt($(this).attr('idx')));
    })
            @if (isset($currentMenu))
            @foreach ($currentMenu as $idx => $menu)
    var master_menu_id = {{$menu['master_menu_id']}};
    $('.menu_item[value='+master_menu_id+']').attr('checked', true);
    $('.menu_item[value='+master_menu_id+']').change();
    @endforeach
    @else
    // $('.page-menu-assign').toggle();
    @endif
    function loadPositionBySeqNo(seq_lst) {
        var prev_id;
        var index;
        var prev_index;
                @if (isset($currentMenu))
                @foreach ($currentMenu as $idx => $menu)
        var master_menu_id = {{$menu['master_menu_id']}};

        // sort by seq_no
        @if (!$loop->first)
            index = seq_lst.indexOf(master_menu_id);
        prev_index = seq_lst.indexOf(prev_id);
        if (index < prev_index) {

            stepDown(master_menu_id, prev_id);
        }
        @endif
            prev_id = master_menu_id;

        @endforeach
        @endif
    }
    var custom_menu_for_message = {};
    var menu_message_clone;
    var flag_updated_menu = 0;
    var master_message_menu ={!! json_encode($masterMenuList) !!};
            @if (isset($currentMenu))
    var selected_menu = {!! json_encode($currentMenu) !!}; // list menu assigned
            @else
    var selected_menu = []; // list menu assigned
    @endif
    // Add or delete object menu, update into custom_menu_for_message
    $('.menu_item').change(function() {
        // get object menu
        var idx = ($(this).parent().attr('idx'));
        var menu = master_message_menu[idx];
        var menu_name_key = menu['menu_name_key'];

        if ($(this).is(":checked")) {
            if (!custom_menu_for_message.hasOwnProperty(menu_name_key)) {
                menu['menu_text'] = $("label[for='master"+idx+"']").first().text();
                custom_menu_for_message[menu_name_key] = menu;
                $('#flag_call_update_menu').val(1);
                flag_updated_menu = 1;
            }

        } else {
            delete custom_menu_for_message[menu_name_key];
            $('#flag_call_update_menu').val(1);
            flag_updated_menu = 1;

        }
    });

    // function to hide or add new menu on message list
    function reload_menu() {
        var custom_menu_for_message_clone = jQuery.extend({}, custom_menu_for_message); // filter
        // check flag_call_update_menu to reload menu
        // if ($('#flag_call_update_menu').val() == 1) {

        $('.school\\.menu').each(function(){

            // remove key not exist on custom_menu_for_message
            var menu_name_key = $(this).children(":first").val();
            if (!custom_menu_for_message.hasOwnProperty(menu_name_key)) {

                $(this).hide();
            } else {

                delete custom_menu_for_message_clone[menu_name_key];
                $(this).show();
            }
        });

        // Add menu

        if (Object.keys(custom_menu_for_message_clone).length > 0) {
            for (x in custom_menu_for_message_clone) {
                var div_clone = $('.school\\.menu').last().clone();
                // Add class to define new item
                div_clone.children().addClass('new-input');
                // change message_key
                div_clone.children(":first").attr('id', 'message_key|'+x);
                div_clone.children(":first").val(x);
                // change message_value
                div_clone.children(":first").next().attr('id', 'message_value|'+x);
                div_clone.children(":first").next().val(custom_menu_for_message_clone[x]['menu_text']);
                // change comment
                div_clone.children(":first").next().next().attr('id', 'comment|'+x);
                div_clone.children(":first").next().next().val('');
                div_clone.insertAfter($('.school\\.menu').last());
                div_clone.show();
            }
        }
        // $('#flag_call_update_menu').val(0);
        // }

    }

    // function to create event onchange of input which menu was added
    function fill_value_menu() {
        // onchange of new menu
        $('input.new-input').change(function() {
            // input_id : {...}|menu_name_key
            var input_id = $(this).attr('id').split('|');

            var idx = custom_menu_for_message.hasOwnProperty(input_id[1]);
            if (idx){
                custom_menu_for_message[input_id[1]][input_id[0]] = $(this).val();
            }

        });

    }
</script>
<script type="text/javascript" src="/js/admin/menu_custom.js"></script>
