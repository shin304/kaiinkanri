    {{--all dialogs--}}
    <div id="dialog_add_bank" class="no_title display_none">
        <div>
            <ul class="bank_message_area">
            </ul>
        </div>
        <table id="table6">
            <colgroup>
                <col width="30%"/>
                <col width="35%"/>
                <col width="35%"/>
            </colgroup>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('bank_code_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="bank_code" value="" class="l_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <b>※{{$lan::get('4_digit_no_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('bank_name_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="bank_name" value="" class="l_text"/>
                </td>
                <td>
                    <b>※ {{$lan::get('15_single_kana_upper_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('branch_code_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="branch_code" value="" class="l_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <b>※ {{$lan::get('3_digit_no_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('bank_branch_name_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="branch_name" value="" class="l_text"/>
                </td>
                <td>
                    <b>※{{$lan::get('15_single_kana_upper_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('classification_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <select name="bank_account_type">
                        @if(isset($bank_account_type_list))
                            @foreach ($bank_account_type_list as $type_id  => $type)
                                <option value="{{$type_id}}">{{$type}}</option>
                            @endforeach
                        @endif
                    </select>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('bank_acc_number_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="bank_account_number" value="" class="m_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <b>※ {{$lan::get('7_digit_no_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('account_name_halfsize')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:active;" type="text" name="bank_account_name_kana" value="" class="l_text"/>
                </td>
                <td>
                    <b>※ {{$lan::get('30_single_kana_upper_title')}}</b>
                </td>

            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('bank_acc_name_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:active;" type="text" name="bank_account_name" value="" class="l_text"/>
                </td>

                <td>
                    <b>{{$lan::get('bank_account_name_kana_warning')}}</b>
                </td>
            </tr>
        </table>
    </div>
    <div id="dialog_add_post" class="no_title display_none">
        <form>
            <table>
                <tr>
                    <td width="150px;" >口座種別</td>
                    <td><label><input type="radio" name="account_type" value="1" checked>総合口座、通常貯金、通常貯蓄貯金</label></td>
                </tr>
                <tr>
                    <td></td>
                    <td><label><input type="radio" name="account_type" value="2">振替口座</label></td>
                </tr>
            </table>
        </form>
        <div>
            <ul class="post_message_area">
            </ul>
        </div>
        <table id="table6" class="type_1_post" style="display: none;">
            <colgroup>
                <col width="30%"/>
                <col width="35%"/>
                <col width="35%"/>
            </colgroup>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_code_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" maxlength="5" type="text" name="post_account_kigou" value="" class="m_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <b>※{{$lan::get('5_digit_no_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_number_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" maxlength="8" name="post_account_number" value="" class="m_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <span><b>※ {{$lan::get('8_digit_no_title')}}</b></span>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_name_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="post_account_name" value="" class="l_text"/>
                </td>
                <td>
                    <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                </td>
            </tr>
        </table>
        <table id="table6" class="type_2_post"  style="display: none;">
            <colgroup>
                <col width="30%"/>
                <col width="35%"/>
                <col width="35%"/>
            </colgroup>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_code_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" maxlength="5" type="text" name="post_account_kigou" value="" class="m_text"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <b>※{{$lan::get('5_digit_no_title')}}</b>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_number_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;width:80px!important;" maxlength="1" type="text" name="post_account_number_1"
                          class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/> &nbsp;
                    <input style="ime-mode:inactive;width:250px!important;"  maxlength="7" type="text" name="post_account_number_2"
                          class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                </td>
                <td>
                    <span><b>※ {{$lan::get('1_plus_7_digit_title')}}</b></span>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('passbook_name_title')}}
                    <span class="aster">*</span>
                </td>
                <td>
                    <input style="ime-mode:inactive;" type="text" name="post_account_name" class="l_text"/>
                </td>
                <td>
                    <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                </td>
            </tr>
            <tr>
                <ul class="post_message_area_{{$key}}">
                </ul>
            </tr>
        </table>
    </div>
    <div id="dialog_remove_bank" class="no_title" style="display:none;">
        {{$lan::get('delete_bank_warning')}}
    </div>
    <div id="show-dialog" class="no_title" style="display: none;">
        {{$lan::get('cannot_delete_last_row')}}
    </div>
    @foreach($bank_data as $key=>$bank)
        <div id="dialog_edit_bank_{{$key}}" class="no_title display_none">
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="35%"/>
                    <col width="35%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('bank_code_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="{{array_get($bank,'id')}}">
                        <input style="ime-mode:inactive;" type="text" name="bank_code"
                               value="{{array_get($bank, 'bank_code')}}" class="l_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('4_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('bank_name_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="bank_name"
                               value="{{array_get($bank, 'bank_name')}}" class="l_text"/>
                    </td>
                    <td>
                        <b>※ {{$lan::get('15_single_kana_upper_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('branch_code_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="branch_code"
                               value="{{array_get($bank, 'branch_code')}}" class="l_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※ {{$lan::get('3_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('bank_branch_name_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="branch_name"
                               value="{{array_get($bank, 'branch_name')}}" class="l_text"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('15_single_kana_upper_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('classification_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <select name="bank_account_type">
                            @if(isset($bank_account_type_list))
                                @foreach ($bank_account_type_list as $type_id  => $type)
                                    <option value="{{$type_id}}"
                                            @if( array_get($bank, 'bank_account_type') == $type_id) selected @endif>{{$type}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('bank_acc_number_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="bank_account_number"
                               value="{{array_get($bank, 'bank_account_number')}}" class="m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※ {{$lan::get('7_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('account_name_halfsize')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:active;" type="text" name="bank_account_name_kana"
                               value="{{array_get($bank, 'bank_account_name_kana')}}" class="l_text"/>
                    </td>
                    <td>
                        <b>※ {{$lan::get('30_single_kana_upper_title')}}</b>
                    </td>

                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('bank_acc_name_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:active;" type="text" name="bank_account_name"
                               value="{{array_get($bank, 'bank_account_name')}}" class="l_text"/>
                    </td>
                    <td>
                        <b>{{$lan::get('bank_account_name_kana_warning')}}</b>
                    </td>
                </tr>
                <tr>
                    <ul class="bank_message_area_{{$key}}">
                    </ul>
                </tr>
            </table>
        </div>
        <div id="dialog_edit_post_{{$key}}" class="no_title display_none">
            <form id="frm_account_type_{{$key}}" class ="frm_account_type">
                <table id="table6">
                    <tr>
                        <td width="150px;" >口座種別</td>
                        <td><label><input type="radio" name="account_type" value="1" @if(array_get($bank, 'post_account_type')!=2) checked @endif/>総合口座、通常貯金、通常貯蓄貯金</label></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><label><input type="radio" name="account_type" value="2" @if(array_get($bank, 'post_account_type')==2) checked @endif>振替口座</label></td>
                    </tr>

                </table>
            </form>
            <table id="table6" class="type_1_post_{{$key}}" style="display: none;">
                <colgroup>
                    <col width="30%"/>
                    <col width="35%"/>
                    <col width="35%"/>
                </colgroup>
                <tr>
                    <ul class="post_message_area_{{$key}}">
                    </ul>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_code_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="{{array_get($bank,'id')}}">
                        <input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"
                               value="{{array_get($bank, 'post_account_kigou')}}" class="post_kigou_1 m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('5_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_number_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" maxlength="8" type="text" name="post_account_number"
                               value="{{array_get($bank, 'post_account_number')}}" class="m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※ {{$lan::get('8_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_name_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="post_account_name"
                               value="{{array_get($bank, 'post_account_name')}}" class=" post_name_1 l_text"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                    </td>
                </tr>
            </table>
            <table id="table6" class="type_2_post_{{$key}}"  style="display: none;">
                <colgroup>
                    <col width="30%"/>
                    <col width="35%"/>
                    <col width="35%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_code_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="{{array_get($bank,'id')}}">
                        <input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"
                               value="{{array_get($bank, 'post_account_kigou')}}" class="post_kigou_2 m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('5_digit_no_title')}}</b>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_number_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;width:80px!important;" maxlength="1" type="text" name="post_account_number_1"
                               value="{{array_get($bank, 'post_account_number_1')}}" class="m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/> &nbsp;
                        <input style="ime-mode:inactive;width:250px!important;"  maxlength="7" type="text" name="post_account_number_2"
                               value="{{array_get($bank, 'post_account_number_2')}}" class="m_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                    <td>
                        <span><b>※ {{$lan::get('1_plus_7_digit_title')}}</b></span>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('passbook_name_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="post_account_name"
                               value="{{array_get($bank, 'post_account_name')}}" class="post_name_2 l_text"/>
                    </td>
                    <td>
                        <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
    <script>

        $(document).ready(function () {
            $(document).on("keyup",".post_kigou_1",function(){
                $(".post_kigou_2").val($(this).val());
            });
            $(document).on("keyup",".post_kigou_2",function(){
                $(".post_kigou_1").val($(this).val());
            });
            $(document).on("keyup",".post_name_1",function(){
                $(".post_name_2").val($(this).val());
            });
            $(document).on("keyup",".post_name_2",function(){
                $(".post_name_1").val($(this).val());
            });
            function select_account_type_post(){
                $(".frm_account_type").each(function(){
                    var frm_id = $(this).attr('id');
                    var key = frm_id.split("_");
                    key = key[3];
                    var type = $(this).find("input[name='account_type']:checked").val();
                    if(type == 1){
                        $(".type_1_post_"+key).show();
                        $(".type_2_post_"+key).hide();
                    }else{
                        $(".type_1_post_"+key).hide();
                        $(".type_2_post_"+key).show();
                    }
                })
                $("input[name='account_type']").click(function () {
                    var frm_id = $(this).closest("form").attr('id');
                    var key = frm_id.split("_");
                    key = key[3];
                    var type = $(this).val();
                    if(type == 1){
                        $(".type_1_post_"+key).show();
                        $(".type_2_post_"+key).hide();
                    }else{
                        $(".type_1_post_"+key).hide();
                        $(".type_2_post_"+key).show();
                    }
                })
            }
            select_account_type_post();
            //			bank + post process
            $("#btn_add_bank").click(function () {
                var arr_id = $(".content_bank_account").find(".content_bank").last().attr('id')
                if (arr_id != null && arr_id != undefined) {
                    arr_id = arr_id.split("_");
                    var length = parseInt(arr_id[2]) + 1; // exists
                } else {
                    var length = 0; // add new
                }
                // clone form
                var tbl_item = $("#form_bank_account").clone(true).appendTo($(".content_bank_account"));
                tbl_item.attr('id', 'content_bank_' + length);
                tbl_item.addClass("content_bank");
                // var countColumn = $(this).closest('div').find('table').find('tbody:last').find('tr').length;
                // if(countColumn == 1) {
                //     // $( "#show-dialog" ).dialog('open');
                //     $('.remove_bank').hide();
                // }
                // else {
                //     $('.remove_bank').show();
                // }
                if(length == 0){
                    tbl_item.find(".default_account").prop("checked",true);
                    $('.remove_bank').hide();
                }else{
                    tbl_item.find(".default_account").attr("disabled","disabled");
                    $('.remove_bank').show();
                }
                tbl_item.find(":input[type=radio]").each(function () {
                    $(this).attr('name', '_bank_type_' + length);
                })
                tbl_item.find(".add_bank").attr('data-new_id',length);
                //clone dialog
                var clone_bank_dialog = $("#dialog_add_bank").clone(true).appendTo("body");
                clone_bank_dialog.attr("id","dialog_add_bank_"+length);

                var clone_post_dialog = $("#dialog_add_post").clone(true).appendTo("body");
                clone_post_dialog.attr("id","dialog_add_post_"+length);
                clone_post_dialog.find("form").attr("id","frm_account_type_"+length);
                clone_post_dialog.find("form").attr("class","frm_account_type");
                clone_post_dialog.find(".type_1_post").attr("class","type_1_post_"+length);
                clone_post_dialog.find(".type_2_post").attr("class","type_2_post_"+length);
                generate_dialog_add(length);
                select_account_type_post();
            });

            //generate 2 dialog when click add button
            function generate_dialog_add(key) {
                $("#dialog_add_bank_"+key).dialog({
                    title: '{{$lan::get('bank_title')}}',
                    width: 925,
                    height: 600,
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    buttons: {
                        "{{$lan::get('run_title')}}": function () {
                            get_data_and_save_bank(key,1,true); // true means this is adding new,1 is bank type
                            return true;
                        },
                        "{{$lan::get('cancel_title')}}": function () {
                            $(this).dialog("close");
                            return false;
                        }
                    }
                });
                $("#dialog_add_post_"+key).dialog({
                    title: '{{$lan::get('post_title')}}',
                    width: 900,
                    height: 390,
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    buttons: {
                        "{{$lan::get('run_title')}}": function () {
                            get_data_and_save_bank(key,2,true); // true means this is adding new,2 is post type
                            return true;
                        },
                        "{{$lan::get('cancel_title')}}": function () {
                            $(this).dialog("close");
                            return false;
                        }
                    }
                });
            }
            $("#dialog_remove_bank").dialog({
                title: '{{$lan::get('main_title')}}',
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "{{$lan::get('run_title')}}": function () {
                        var bank_id = $(this).data('bank_id');
                        if(bank_id!=null && bank_id!=undefined){
                           //ajax remove bank
                            var _token = "{{csrf_token()}}";
                            var this_dlg=  $(this);

                            $.ajax({
                                type: "post",
                                url: "/school/school/ajax_remove_bank",
                                data: {bank_id:bank_id, _token: _token},
                                success: function(data) {
                                    $("#dialog_remove_bank").data('target').remove();
                                    this_dlg.dialog("close");
                                    var countColumn = $('#btn_add_bank').closest('div').find('table').find('tbody:last').find('tr').length;
                                    console.log(countColumn);
                                    if(countColumn == 1) {
                                        $('.remove_bank').hide();
                                    }
                                    return false;
                                }
                            });

                        }else{
                            $("#dialog_remove_bank").data('target').remove();
                            $(this).dialog("close");
                        }
                        // var countColumn = $('#btn_add_bank').closest('div').find('table').find('tbody:last').find('tr').length;
                        // console.log(countColumn);
                        // if(countColumn == 1) {
                        //     $('.remove_bank').hide();
                        // }
                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });
            @foreach($bank_data as $key=>$bank)
                generate_edit_dialog({{$key}});
            @endforeach
            function generate_edit_dialog(key){
                $("#dialog_edit_bank_"+key).dialog({
                    title: '{{$lan::get('bank_title')}}',
                    width: 925,
                    height: 600,
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    buttons: {
                        "{{$lan::get('run_title')}}": function () {
                            get_data_and_save_bank(key,1,false); // false means this is editing old ,1 is bank type
                            return true;
                        },
                        "{{$lan::get('cancel_title')}}": function () {
                            $(this).dialog("close");
                            return false;
                        }
                    }
                });

                $("#dialog_edit_post_"+key).dialog({
                    title: '{{$lan::get('post_title')}}',
                    width: 900,
                    height: 390,
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    buttons: {
                        "{{$lan::get('run_title')}}": function () {
                            get_data_and_save_bank(key,2,false); // false means this is editing old ,2 is post type
                            return true;
                        },
                        "{{$lan::get('cancel_title')}}": function () {
                            $(this).dialog("close");
                            return false;
                        }
                    }
                });
            }
            //ajax save bank account
            function get_data_and_save_bank(key,type,isNew) {
                var data= [];
                var data_view=[] ; // update view when complete
                if(isNew){
                    if(type==1){
                        $("#dialog_add_bank_"+key).find("input").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                        $("#dialog_add_bank_"+key).find("select").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                    }else if(type==2){
                        var post_account_type = $("#frm_account_type_"+key).find($("input[name='account_type']:checked")).val();
                        data.push(['post_account_type',post_account_type]);

                        $("#dialog_add_post_"+key).find("input[type='text']").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                    }
                }else{
                    if(type==1){
                        $("#dialog_edit_bank_"+key).find("input").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                        $("#dialog_edit_bank_"+key).find("select").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                    }else if(type==2){
                        var post_account_type = $("#frm_account_type_"+key).find($("input[name='account_type']:checked")).val();
                        data.push(['post_account_type',post_account_type]);

                        $("#dialog_edit_post_"+key).find("input[type='hidden']").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                        $("#dialog_edit_post_"+key).find("input[type='text']").each(function () {
                            var input = [$(this).attr('name'),$(this).val()];
                            data.push(input);
                        })
                    }
                }
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/school/ajax_save_bank_account",
                    data: {bank_data:data,bank_type:type,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status==false){
                            var errorsHtml = '';
                            $.each( data.errors , function( key, value ) {
                                errorsHtml+='<li class="error_message">'+value+'</li>';
                            });
                            if(isNew){
                                if(type==1){
                                    $(".bank_message_area").html('');
                                    $(".bank_message_area").append(errorsHtml);
                                }else{
                                    $(".post_message_area").html('');
                                    $(".post_message_area").append(errorsHtml);
                                }
                                return false;
                            }else{
                                if(type==1){
                                    $(".bank_message_area_"+key).html('');
                                    $(".bank_message_area_"+key).append(errorsHtml);
                                }else{
                                    $(".post_message_area_"+key).html('');
                                    $(".post_message_area_"+key).append(errorsHtml);
                                }
                                return false;
                            }
                        }else if(data.status==true){
                            var new_bank_id = data.message;
                            if(isNew) {
                                if(type==1) {
                                    $(".bank_message_area").html('');
                                    reload_content_bank();
                                    $("#dialog_add_bank_"+key).dialog("close");

                                }else{
                                    $(".post_message_area").html('');
                                    reload_content_bank();
                                    $("#dialog_add_post_"+key).dialog("close");
                                }
                            }else{
                                if(type==1) {
                                    $(".bank_message_area_" + key).html('');
                                    reload_content_bank();
                                    $("#dialog_edit_bank_"+key).dialog("close");

                                }else{
                                    $(".post_message_area_" + key).html('');
                                    reload_content_bank();
                                    $("#dialog_edit_post_"+key).dialog("close");
                                }
                            }
                            return false;
                        }
                    }
                });
            }
            // ajax reload content bank
            function reload_content_bank() {
                var _token = "{{csrf_token()}}";
                $.ajax({
                    type: "post",
                    url: "/school/school/ajax_get_all_bank_account",
                    data: {_token: _token},
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==true){
                            var list_bank = data.message;
                            var dom_bank = "";
                            list_bank.forEach(function(item,index){
                                create_edit_dom(item,index);
                               if(index==0){
                                   dom_bank +='<tr id="content_bank_'+index+'" class="content_bank">';
                               }else{
                                   dom_bank +='<tr id="content_bank_'+index+'" class="content_bank">';
                               }
                               if(item.is_default_account==1){
                                   dom_bank+='<td style="text-align:center"><label><input type="radio" data-bank_id="'+item.id+'" class="default_account"'
                                       +'checked></label></td>';
                               }else{
                                   dom_bank+='<td style="text-align:center"><label><input type="radio" data-bank_id="'+item.id+'" class="default_account"></label></td>';
                               }
                               dom_bank+='<td><form>';
                               if(item.bank_type==1){
                                   dom_bank+='<label><input type="radio" name="_bank_type" value="1" checked >{{$lan::get('new_bank_title')}}</label> ';
                                   dom_bank+='<label><input type="radio" name="_bank_type" value="2">{{$lan::get('post_title')}}</label>';
                               }else{
                                   dom_bank+='<label><input type="radio" name="_bank_type" value="1" >{{$lan::get('new_bank_title')}}</label> ';
                                   dom_bank+='<label><input type="radio" name="_bank_type" value="2" checked >{{$lan::get('post_title')}}</label>';
                               }
                               dom_bank+="</form></td>";
                                if(item.bank_type==1) {
                                    dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.bank_name + '"></td>';
                                    dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.branch_name + '"></td>';
                                    dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.bank_account_number + '"></td>';
                                }else{
                                    if(item.post_account_type == 1){
                                        dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.post_account_kigou + '"></td>'
                                        dom_bank += '<td><input type="text" class="input_text" disabled value=""></td>';
                                        dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.post_account_number + '"></td> ';
                                    }else{
                                        dom_bank += '<td><input type="text" class="input_text" disabled value="' + item.post_account_kigou + '"></td>'
                                        dom_bank += '<td><input type="text" class="input_text" disabled value="' + (item.post_account_number).substr(0,1) + '"></td>';
                                        dom_bank += '<td><input type="text" class="input_text" disabled value="' + (item.post_account_number).substr(1,7) + '"></td> ';
                                    }
                                }
                                dom_bank+='<td><input type="button" data-bank_id="'+index+'" class="edit_bank" value="{{$lan::get('edit_title')}}">&nbsp; ';
                                dom_bank+='<input type="button" data-bank_id="'+item.id+'" class="remove_bank" value="{{$lan::get('delete_title')}}"></td>';
                                dom_bank+='</tr>';
                            })
                            $(".content_bank_account").html('');
                            $(".content_bank_account").html(dom_bank);
                            btn_click_able();
                        }
                    }
                });
            }
            btn_click_able();
            function btn_click_able(){
                // radio check for default : uncheck all then check this
                $(".default_account").click(function () {
                    $(".default_account").each(function () {
                        $(this).prop("checked",false);
                    })
                    $(this).prop("checked",true);

                    var _token = "{{csrf_token()}}";
                    var bank_id=$(this).data("bank_id");
                    $.ajax({
                        type: "post",
                        url: "/school/school/ajax_change_default_bank_account",
                        data: {bank_id:bank_id,_token: _token},
                        dataType:'json',
                        success: function(data) {

                        }
                    });
                })
                $(".edit_bank").click(function () {
                    var target = $(this).data('bank_id');
                    var type = 0;
                    var check_type = $(this).closest(".content_bank").find("input[name=_bank_type]:checked");
                    type = check_type.val();
                    if (type == 1) {
                        $("#dialog_edit_bank_" + target).dialog('open');
                    } else {
                        $("#dialog_edit_post_" + target).dialog('open');
                    }
                })
//                $(".add_bank").click(function () {
//                    var target = $(this).data('new_id');
//                    var type = 0;
//                    var check_type = $(this).closest("form").find("input[type=radio]:checked");
//                    type = check_type.val();
//                    console.log($(this).html());
//                    return;
//                    if (type == 1) {
//                        $("#dialog_add_bank_"+target).dialog('open');
//                    } else {
//                        $("#dialog_add_post_"+target).dialog('open');
//                    }
//                })
                $(document).on("click",".add_bank",function () {
                    var target = $(this).data('new_id');
                    var type = 0;
                    var check_type = $("input[name=_bank_type_"+target+"]:checked", '.myForm');
                    type = check_type.val();
                    if (type == 1) {
                        $("#dialog_add_bank_"+target).dialog('open');
                    } else {
                        $("#dialog_add_post_"+target).dialog('open');
                    }
                });
                $( "#show-dialog" ).dialog({
                    title: '{{$lan::get('main_title')}}',
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    buttons: {
                        "{{$lan::get('cancel_title')}}": function () {
                            $(this).dialog("close");
                            return false;
                        }
                    }
                });
                $(".remove_bank").click(function () {
                    var target = $(this).closest(".content_bank");
                    var bank_id = $(this).data('bank_id');
                    if(bank_id!=null && bank_id!=undefined){
                        $("#dialog_remove_bank").data('bank_id',bank_id)//delete database item
                    }
                    $("#dialog_remove_bank").data('target',target).dialog('open');
                });
            }
            function create_edit_dom(bank,index){
                if($("#dialog_edit_bank_"+index) != undefined) $("#dialog_edit_bank_"+index).remove();
                if($("#dialog_edit_post_"+index) != undefined) $("#dialog_edit_post_"+index).remove();
               $.each(bank,function(key,value){
                    if(value==null || value=="null" || value=="NULL" || value == undefined){
                        bank[key] = "";
                    }
                });
                var dom="";
                dom+='<div id="dialog_edit_bank_'+index+'" class="no_title display_none">'+
                    '<table id="table6"> <colgroup> <col width="30%"/> <col width="35%"/> <col width="35%"/> </colgroup>'+
                    '<tr> <td class="t6_td1">{{$lan::get('bank_code_title')}}<span class="aster">*</span> </td>'+
                    '<td><input type="hidden" name="id" value="'+bank.id+'">'+
                    '<input style="ime-mode:inactive;" type="text" name="bank_code"'+
                    'value="'+bank.bank_code+'" class="l_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>'+
                    '</td><td> <b>※{{$lan::get('4_digit_no_title')}}</b>'+
                    '</td> </tr> <tr> <td class="t6_td1">{{$lan::get('bank_name_title')}}'+
                    '<span class="aster">*</span> </td> <td>'+
                    '<input style="ime-mode:inactive;" type="text" name="bank_name"'+
                    'value="'+bank.bank_name+'" class="l_text"/>'+
                    '</td> <td> <b>※ {{$lan::get('15_single_kana_upper_title')}}</b>'+
                    '</td></tr><tr><td class="t6_td1">{{$lan::get('branch_code_title')}}<span class="aster">*</span></td>'+
                    '<td> <input style="ime-mode:inactive;" type="text" name="branch_code"'+
                    'value="'+bank.branch_code+'" class="l_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/></td>'+
                    '<td> <b>※ {{$lan::get('3_digit_no_title')}}</b></td></tr>'+
                    '<tr> <td class="t6_td1">{{$lan::get('bank_branch_name_title')}}<span class="aster">*</span> </td>'+
                    '<td> <input style="ime-mode:inactive;" type="text" name="branch_name"'+
                    'value="'+bank.branch_name+'" class="l_text"/> </td>'+
                    '<td><b>※{{$lan::get('15_single_kana_upper_title')}}</b> </td></tr>'+
                    '<tr><td class="t6_td1">{{$lan::get('classification_title')}}<span class="aster">*</span> </td>'+
                    '<td><select name="bank_account_type">';

                    @foreach ($bank_account_type_list as $type_id  => $type)
                        dom+='<option value="{{$type_id}}"';
                        if(bank.bank_account_type=={{$type_id}}){
                            dom+='selected';
                        }
                        dom+='>{{$type}}</option>';
                    @endforeach

                dom+=' </select></td></tr>'+
                    '<tr> <td class="t6_td1">{{$lan::get('bank_acc_number_title')}}<span class="aster">*</span> </td>'+
                    '<td> <input style="ime-mode:inactive;" type="text" name="bank_account_number"'+
                    'value="'+bank.bank_account_number+'" class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/></td>'+
                    '<td> <b>※ {{$lan::get('7_digit_no_title')}}</b> </td> </tr>'+
                    '<tr><td class="t6_td1">{{$lan::get('account_name_halfsize')}}<span class="aster">*</span> </td>'+
                    '<td> <input style="ime-mode:active;" type="text" name="bank_account_name_kana"'+
                    'value="'+bank.bank_account_name_kana+'" class="l_text"/></td>'+
                    '<td><b>※ {{$lan::get('30_single_kana_upper_title')}}</b></td></tr>'+
                    '<tr><td class="t6_td1">{{$lan::get('bank_acc_name_title')}}<span class="aster">*</span> </td>'+
                    '<td><input style="ime-mode:active;" type="text" name="bank_account_name"'+
                    'value="'+bank.bank_account_name+'" class="l_text"/> </td>'+
                    '<td><b>{{$lan::get('bank_account_name_kana_warning')}}</b></td> </tr> <tr>'+
                    '<ul class="bank_message_area_'+index+'"> </ul> </tr> </table> </div>';

                dom+='<div id="dialog_edit_post_'+index+'" class="no_title display_none">';
                dom+='<form id="frm_account_type_'+index+'" class ="frm_account_type"><table id="table6"> <tr> <td width="150px;" >口座種別</td>';

                if(bank.post_account_number_1 === undefined){
                    bank.post_account_number_1 = "";
                }
                if(bank.post_account_number_2 === undefined){
                    bank.post_account_number_2 = "";
                }
                if(bank.post_account_type != 2){
                    dom+='<td><label><input type="radio" name="account_type" value="1" checked>総合口座、通常貯金、通常貯蓄貯金</label></td>'
                    +'</tr> <tr> <td></td> <td><label><input type="radio" name="account_type" value="2">振替口座</label></td>';
                }else{
                    dom+='<td><label><input type="radio" name="account_type" value="1">総合口座、通常貯金、通常貯蓄貯金</label></td>'
                    +'</tr> <tr> <td></td> <td><label><input type="radio" name="account_type" value="2" checked>振替口座</label></td>';
                }


                dom+='</tr> </table></form>';
                dom+='<table id="table6" class="type_1_post_'+index+'" style="display: none;" > <colgroup> <col width="30%"/> <col width="35%"/> <col width="35%"/> </colgroup>'+
                    '<tr><td class="t6_td1">{{$lan::get('passbook_code_title')}}<span class="aster">*</span> </td>'+
                    '<td> <input type="hidden" name="id" value="'+bank.id+'">'+
                    '<input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"'+
                    'value="'+bank.post_account_kigou+'" class="post_kigou_1 m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/> </td>'+
                    '<td> <b>※{{$lan::get('5_digit_no_title')}}</b> </td> </tr>'+
                    '<tr> <td class="t6_td1">{{$lan::get('passbook_number_title')}}<span class="aster">*</span> </td>'+
                    '<td> <input style="ime-mode:inactive;" maxlength="8" type="text" name="post_account_number"'+
                    'value="'+bank.post_account_number+'" class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/> </td>'+
                    '<td> <b>※ {{$lan::get('8_digit_no_title')}}</b> </td> </tr>'+
                    '<tr> <td class="t6_td1">{{$lan::get('passbook_name_title')}}<span class="aster">*</span> </td>'+
                    '<td> <input style="ime-mode:inactive;" type="text" name="post_account_name"'+
                    'value="'+bank.post_account_name+'" class="post_name_1 l_text"/> </td>'+
                    '<td> <b>※{{$lan::get('30_single_kana_upper_title')}}</b> </td> </tr>'+
                    '<tr> <ul class="post_message_area_'+index+'"> </ul> </tr> </table>';
                dom+='<table id="table6" class="type_2_post_'+index+'"  style="display: none;">'
                    +'<colgroup> <col width="30%"/> <col width="35%"/> <col width="35%"/> </colgroup>'
                    +'<tr> <td class="t6_td1">{{$lan::get('passbook_code_title')}}<span class="aster">*</span> </td>'
                    +'<td> <input type="hidden" name="id" value="'+bank.id+'">'
                    +'<input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"'
                    +'value="'+bank.post_account_kigou+'" class="post_kigou_2 m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/></td>'
                    +'<td><b>※{{$lan::get('5_digit_no_title')}}</b> </td> </tr>'
                    +'<tr> <td class="t6_td1">{{$lan::get('passbook_number_title')}}<span class="aster">*</span> </td>'
                    +'<td><input style="ime-mode:inactive;width:80px!important;" maxlength="1" type="text" name="post_account_number_1"'
                    +'value="'+bank.post_account_number_1+'" class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/> &nbsp;'
                    +'<input style="ime-mode:inactive;width:250px!important;"  maxlength="7" type="text" name="post_account_number_2"'
                    +'value="'+bank.post_account_number_2+'" class="m_text" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/></td>'
                    +'<td> <b>※ {{$lan::get('8_digit_no_title')}}</b> </td> </tr><tr>'
                    +'<td class="t6_td1">{{$lan::get('passbook_name_title')}}<span class="aster">*</span></td>'
                    +'<td> <input style="ime-mode:inactive;" type="text" name="post_account_name"'
                    +'value="'+bank.post_account_name+'" class="post_name_2 l_text"/></td>'
                    +'<td><b>※{{$lan::get('30_single_kana_upper_title')}}</b> </td></tr>'
                    +'<tr><ul class="post_message_area_'+index+'"> </ul> </tr></table></div>';
                    $("body").append(dom);

                generate_edit_dialog(index);
                select_account_type_post();
            }
        })
    </script>