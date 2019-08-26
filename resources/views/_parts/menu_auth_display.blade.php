
<div id="section_content1">
    <div><b>{{$lan::get('menu_structure')}}</b></div>
    <ul id="filter-menu" class="ul-column-title-view" style="height: auto; width: 40%;">
        <!-- list exist list menu -->
        @if (count(old('defaultMenuList', request('defaultMenuList'))) > 0)
        @foreach(old('defaultMenuList', request('defaultMenuList')) as $dmenu)

            <li idx="{{$dmenu['master_menu_id']}}"  class="filter-row menu-{{$dmenu['menu_name_key']}}" >{{$lan::get($dmenu['menu_name_key'])}}

                <!--  create edit button -->
                @if (array_get($dmenu, 'master_editable') == 1)
                <span class="btn_auth">
                    <i class="fa @if (array_get($dmenu, 'editable')) fa-check @else fa-close @endif"></i><i class="fa fa-edit"></i>
                </span>
                @endif
                <!--  create view button -->
                <span class="btn_auth">
                    <i class="fa @if (array_get($dmenu, 'viewable')) fa-check @else fa-close @endif"></i><i class="fa fa-eye"></i>
                </span>

                <!-- SUBMENU LIST -->
                @if(array_key_exists($dmenu['master_menu_id'], old('defaultSubMenuList', request('defaultSubMenuList')) ))
                    <ul id="sub-reference-menu" >
                        @foreach(old('defaultSubMenuList', request('defaultSubMenuList'))[$dmenu['master_menu_id']] as $smenu)
                            <li idx="{{$smenu['master_menu_id']}}" >{{$lan::get($smenu['menu_name_key'])}}

                            <!--  create edit button -->
                            @if (array_get($dmenu, 'master_editable') == 1) <!--  depend on parent => $dmenu[master_editable]-->
                                <span class="btn_auth">
                                    <i class="fa @if (array_get($smenu, 'editable')) fa-check @else fa-close @endif"></i><i class="fa fa-edit"></i>
                                </span>
                            @endif
                            <!--  create view button -->
                                <span class="btn_auth">
                                    <i class="fa @if (array_get($smenu, 'viewable')) fa-check @else fa-close @endif"></i><i class="fa fa-eye"></i>
                                </span>

                        </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
        @endif
    </ul>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="/css{{$_app_path}}menu_setting.css" />
<style rel="stylesheet">
    .ul-column-title-view li {
        font-weight: 500;
    }
</style>
