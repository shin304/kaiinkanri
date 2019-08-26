<div id="payment_template_dialog">
    <div id="payment_template_table">
        <form id="payment_method_form" method="post" name="payment_method_form" enctype="multipart/form-data">
        </form>
    </div>
</div>
<div id="unsetting_post_office_dialog" style="display: none;">
    {{$lan::get('unsetting_post_office_error')}}
</div>
<style>
    .error_message{
        margin-left: 15px;
    }

    .ui-dialog-buttonpane button {
        width:100px;
        height:30px !important;
    }
</style>
<script>
    $(function () {
        $("#unsetting_post_office_dialog").dialog({
            title: '{{$lan::get('edit_payment_title')}}',
            width: 400,
            autoOpen: false,
            dialogClass: "no-close",
            resizable: true,
            modal: true,
            buttons: {
                "OK": function () {
                    $(this).dialog("close");
                    return true;
                }
            }
        })
        click_able_button();
        set_payment_method();
        function create_dialog(){
            $("#payment_template_dialog").dialog({
                title: '{{$lan::get('edit_payment_title')}}',
                width: 900,
                autoOpen: false,
                dialogClass: "no-close",
                resizable: true,
                modal: true,
                buttons: {
                    "{{$lan::get('run_title')}}": function () {
                        $.ajax({
                            type: "post",
                            url: "/school/school/save_detail_payment",
//                            data: {payment_method_id:payment_method_id,agency_id:index,arr_inputs:arr_inputs,_token: _token},
                            data: $('#payment_method_form').serialize(),
                            dataType:'json',
                            success: function(data) {
                                if(data.status==true){
                                    reload_payment_method_dom();
                                    $("#payment_template_dialog").dialog("close");
                                }else if(data.status=='validation_fail'){
                                    var warning_dom ="";
                                    $.each(data.message,function(key,value){
                                        warning_dom+='<li class="error_message">'+value+'</li>';
                                    })
                                    $(".payment_message_area").html('');
                                    $(".payment_message_area").append(warning_dom);
                                    return false;
                                }
                            }
                        });
                        return true;
                    },
                    "{{$lan::get('cancel_title')}}": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });
            
        }
        function click_able_button(){
            $('.payment_edit').click(function(){
                var _token = "{{csrf_token()}}";
                var payment_code = $(this).data("payment_code");
                $.ajax({
                    type: "post",
                    url: "/school/school/get_detail_payment_method",
                    data: {_token: _token,payment_code:payment_code},
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==true){
                            var dom =generate_payment_dom(data.message);
                            $("#payment_template_table form").html(dom);
                            $('#agency_id').change(function () {
                                display_div_agency();
                            })
                            display_div_agency();
                            create_dialog();
//                            change_default_bank_of_method();
                            $("#payment_template_dialog").dialog('open');
                        } else {
                            $("#unsetting_post_office_dialog").dialog('open');
                        }
                    }
                });
            })
        }


        function generate_payment_dom(data){
            var dom="";
            var bank_dom="";
            // generate just only bank list (code = TRAN_BANK )
            if(Object.keys(data)[0]=="TRAN_BANK"){
                $.each(data,function(key,value){
                    $.each(value,function(index,item) {
                        if (index == "bank_info") {
                            bank_dom = '<div>';
                            bank_dom += "<h5>{{$lan::get('account_select_title')}}</h5>";
    //                        bank_dom+='<form class="'+item.payment_agency_id+'"><table id="table6" class="list_bank list_bank_'+item.payment_agency_id+'">';
                            bank_dom += '<table id="table6" class="">';
                            bank_dom += '<colgroup><col width="30%"/><col width="30%"/><col width="30%"/>';
                            if (item.list_bank) {
                                item.list_bank.forEach(function (value, key) {
                                    bank_dom += '<tr>';
                                    bank_dom += '<td><input type="radio" name="default_bank" ' + ((value.is_default_bank == 1 || key == 0) ? 'checked' : '') + ' class="check_default_bank" value=' + value.id + '>';
                                    bank_dom += '&nbsp;' + value.bank_name + '</td>';
                                    bank_dom += '<td>' + value.branch_name + '</td>';
                                    bank_dom += '<td>' + value.account_number + '</td>';
                                    bank_dom += '</tr>';

                                })
                            }
    //                        bank_dom+='</table></form>';
                            bank_dom += '</table></div>';
                        }
                    })
                })
                dom+='<div>{{ csrf_field() }}';
                dom+='<input type="hidden" name="payment_method_id" value ="'+data.TRAN_BANK.bank_info.id+'">';
                dom+=bank_dom;
                dom+='</div>';
                return dom;
            }
            //generate agency
            var dom_agency="";
            dom_agency+='<tr><td>{{$lan::get('payment_agency_title')}}</td><td><select id ="agency_id" name ="agency_id">';
            $.each(data,function(key,value){
                     dom_agency+= '<option value="'+value[0].payment_agency_id+'"';
                     if(value[0].payment_agency_id==value[0].default_agency){
                         dom_agency+='selected>'+value[0].agency_name+'</option>';
                     }else{
                         dom_agency+='>'+value[0].agency_name+'</option>';
                     }
            })

            //generat input field
            var dom_input="";

            $.each(data,function(key,value){// value is object
                dom_input+='<table id="table6" class="agency_'+value[0].payment_agency_id+' div_agency">';
                dom_input+='<input type="hidden" name="payment_method_id" value ="'+value[0].payment_method_id+'">';
                dom_input+='<colgroup><col width="20%"/><col width="50%"/><col width="30%"/>';
                $.each(value,function (index,item) { // item is object
                    if(index!="bank_info"){
                        if(item.item_type==1){ // 1:type = text
                            dom_input+='<tr>' +
                                '<td>'+item.item_display_name+'</td>' +
                                '<td><input type="text" class="inputs_div" name="inputs_div['+item.item_name+']" value="'+item.item_value+'"';
                            dom_input+='data-payment_setting="'+item.payment_setting_id+'"';
                            if(item.payment_data_id !="" && item.payment_data_id !=null && item.payment_data_id !=undefined){
                                dom_input+=' data-data_id="'+item.payment_data_id+'"';
                            }
                            dom_input+='>&nbsp;'+item.unit+'</td><td>'+item.note+'</td></tr>';
                        }else if(item.item_type==2){ //2: type =select box
                            //generate option defaul
                            var option=[];
                            var default_option =  item.default_value.split(';');
                            default_option.forEach(function (value,key) {
                                var temp=[];
                                temp['key']=value.split(':')[0];
                                temp['value']=value.split(':')[1];
                                option.push(temp);
                            })

                            // push to dom
                            dom_input+='<tr>' +
                                '<td>'+item.item_display_name+'</td>' +
                                '<td><select class="inputs_div" name="inputs_div['+item.item_name+']"';
                            dom_input+='data-payment_setting="'+item.payment_setting_id+'"';
                            if(item.payment_data_id !="" && item.payment_data_id !=null && item.payment_data_id !=undefined){
                                dom_input+=' data-data_id="'+item.payment_data_id+'"';
                            }
                            dom_input+='>';
                            option.forEach(function (value,key){
                                if(value['key']==item.item_value){
                                    dom_input+='<option value="'+value['key']+'" selected>'+value['value']+'</option>';
                                }else{
                                    dom_input+='<option value="'+value['key']+'">'+value['value']+'</option>';
                                }
                            })
                            dom_input+='</select>&nbsp;'+item.unit+'</td><td>'+item.note+'</td></tr>';
                        }else if(item.item_type==3){ // 3: text area
                            dom_input+='<tr>' +
                                '<td>'+item.item_display_name+'</td>' +
                                '<td><textarea class="inputs_div" name="inputs_div['+item.item_name+']" ';
                            dom_input+='data-payment_setting="'+item.payment_setting_id+'"';
                            if(item.payment_data_id !="" && item.payment_data_id !=null && item.payment_data_id !=undefined){
                                dom_input+=' data-data_id="'+item.payment_data_id+'"';
                            }
                            dom_input+='>'+item.item_value+'</textarea>&nbsp;'+item.unit+'</td><td>'+item.note+'</td></tr>';
                        }else if(item.item_type==4){ //4: type =radio button
                            //generate option defaul
                            var option= JSON.parse(item.default_value);
                            //push to dom
                            dom_input+='<tr>' +
                                '<td>'+item.item_display_name+'</td>' +
                                '<td>';
                            $.each(option, function(key, val) {
                                if(val == 'used_title'){
                                    dom_input+='&nbsp;&nbsp;<label style="font-weight: 500;"><input class="inputs_div"  type="radio" name="inputs_div['+item.item_name+']" value="'+key+'" disabled>&nbsp;'+val+'</label>';
                                }
                                else if(key==item.item_value || key == 1){
                                    dom_input+='&nbsp;&nbsp;<label style="font-weight: 500;"><input class="inputs_div"  type="radio" name="inputs_div['+item.item_name+']" checked value="'+key+'" selected>&nbsp;'+val+'</label>';
                                }else{
                                    dom_input+='&nbsp;&nbsp;<label style="font-weight: 500;"><input class="inputs_div"  type="radio" name="inputs_div['+item.item_name+']" value="'+key+'">&nbsp;'+val+'</label>';
                                }
                            });

                            dom_input+='</td><td>'+item.note+'</td></tr>';
                        }
                        dom_input+= '<input type="hidden" name="payment_setting_id['+item.item_name+']" value="'+item.payment_setting_id+'">';
//                          else if(item.item_type==4){ // 4:check boxes
//                            var option=[];
//                            var default_option =  item.default_value.split(';');
//                            default_option.forEach(function (value,key) {
//                                var temp=[];
//                                temp['key']=value.split(':')[0];
//                                temp['value']=value.split(':')[1];
//                                option.push(temp);
//                            })
//
//                            // push to dom
//                            dom_input+='<tr>' +
//                                '<td>'+item.item_display_name+'</td>' +
//                                '<td>';
//                            option.forEach(function (value,key){
//                                if(value['key']==item.item_value!=0){
//                                    dom_input+='<input type="checkbox" value="'+value['key']+'" checked>&nbsp;'+value['value']+'&nbsp;';
//                                }else{
//                                    dom_input+='<input type="checkbox" value="'+value['key']+'">&nbsp;'+value['value']+'&nbsp;';
//                                }
//                            })
//                            dom_input+='</select></td></tr>';
//                        }
                    }
                    if(index=="bank_info" && item.list_bank){
                        bank_dom = '<div class="list_bank list_bank_'+value[0].payment_agency_id+'">';
                        bank_dom+="<h5>{{$lan::get('account_select_title')}}</h5>";
//                        bank_dom+='<form class="'+item.payment_agency_id+'"><table id="table6" class="list_bank list_bank_'+item.payment_agency_id+'">';
                        bank_dom+='<table id="table6" class="">';
                        bank_dom+='<colgroup><col width="30%"/><col width="30%"/><col width="30%"/>';
                        if(item.list_bank){
                            item.list_bank.forEach(function (value,key) {
                                bank_dom+='<tr>';
                                bank_dom+='<td><input type="radio" name="default_bank" '+ ((value.is_default_bank==1 || key==0)? 'checked': '')+ ' class="check_default_bank" value='+value.id+'>';
                                bank_dom+='&nbsp;'+value.bank_name+'</td>';
                                bank_dom+='<td>'+value.branch_name+'</td>';
                                bank_dom+='<td>'+value.account_number+'</td>';
                                bank_dom+='</tr>';

                            })
                        }
//                        bank_dom+='</table></form>';
                        bank_dom+='</table></div>';
                    }
                })
                dom_input+='</table>';
                dom_input+=bank_dom;
            });
            //merge all
            dom_agency+='</select>';
            // dom total
            dom+='<div>{{ csrf_field() }}<ul class="payment_message_area"></ul><table id="table6">';
            dom+='<colgroup><col width="30%"/><col width="70%"/>';
            dom+=dom_agency;
            dom+='</table>';
            dom+=dom_input;
            dom+='</div>';
            return dom;
        }
        function display_div_agency(){
            $('.div_agency').hide();
            $('.list_bank').hide();
            var index = $('#agency_id  option:selected').val();
            $('.agency_'+index).show();
            $('.list_bank_'+index).show();
        }
        function reload_payment_method_dom(){
            var _token = "{{csrf_token()}}";
            $.ajax({
                type: "post",
                url: "/school/school/get_pschool_payment_method",
                data: {_token: _token},
                dataType: 'json',
                success: function (data) {
                    if(data.status==true){
                        var methods = data.message;
                        var dom = '<colgroup> <col width="20%"/> <col width="40%"/> <col width="40%"/> </colgroup>';
                        methods.forEach(function(item,index){
                            dom+='<tr><td>'+
                                '<input type="checkbox" data-agency_id="'+item.payment_agency_id+'" class="set_payment_method" name="payment_method_id" value="'+item.id+'"';
                            if(item.default==1){
                                dom+=' checked>'+item.name;
                            }else{
                                dom+='>'+item.name;
                            }
                            dom+='</td>';
                            if(item.code=='CASH'){
                                dom+='<td></td><td></td>';
                            }
                            else if(item.code=='TRAN_BANK'){
                                dom+='<td></td><td> <input type="button" class="payment_edit" data-payment_code="'+item.code+'" value="編集"></td>';
                            }else{
                                dom+='<td> <input type="text" disabled value="'+item.agency_name+'"> </td>';
                                dom+='<td> <input type="button" class="payment_edit" data-payment_code="'+item.code+'" value="編集"></td>';
                            }
                            dom+='</tr>';
                        })
                        $(".table_payment_methods").html();
                        $(".table_payment_methods").html(dom);
                        click_able_button();
                        set_payment_method();
                    }
                }
            });
        }
        function change_default_bank_of_method(){
            $(".check_default_bank").change(function () {
                if($(this).is(":checked")){
                    var bank_id = $(this).data('bank_id');
                    var payment_method_id = $(this).attr('name');
                    payment_method_id = payment_method_id.split("_")[2];
                    var _token = "{{csrf_token()}}";
                    $.ajax({
                        type: "post",
                        url: "/school/school/set_default_bank_method",
                        data: {payment_method_id:payment_method_id,bank_id:bank_id,_token: _token},
                        dataType: 'json',
                        success: function (data) {

                        }
                    });
                }
            })
        }
        function set_payment_method(){
            $(".set_payment_method").change(function(){
                if($(this).is(':checked')){
                    var is_delete= false;
                }else{
                    var is_delete = true;
                }
                var payment_method_id = $(this).val();
                var agency_id = $(this).data('agency_id');
                var _token = "{{csrf_token()}}";
                $.ajax({
                    type: "post",
                    url: "/school/school/set_payment_method",
                    data: {payment_method_id:payment_method_id,agency_id:agency_id,is_delete:is_delete,_token: _token},
                    dataType: 'json',
                    success: function (data) {

                    }
                });
            })
        }
    })
</script>