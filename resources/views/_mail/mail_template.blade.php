<script type="text/javascript" src="/js/school/mail_template.js"></script>

<div id="mail_template_dialog" class="display_none" aria-hidden="true">
    <div class="search_box box_border1 padding1">
        <table id="search_table">
            <tr>
                <th> {{$lan::get('mail_template_type')}}&nbsp;</th>
                <td>
                    <select id="search_by_type" name="type_mail">
                        <option value="" selected="selected">{{$lan::get('mail_template_option')}}</option>
                        @foreach($mail_template_type as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </div>
    <br />
    <div id="delete_error" class="alert alert-warning" style="display: none;">{{$lan::get('mail_template_error')}}</div>
    <div id="delete_success" class="alert alert-success" style="display: none;">{{$lan::get('mail_template_delete_success')}}</div>
    <table class="table1" id="table">
        <thead>
        <tr>
            <th style="padding-left: 10px;" class="text_left">{{$lan::get('mail_template_name_title')}}</th>
            <th class="text_left">{{$lan::get('mail_template_type')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div id="mail_template_create_dialog" class="display_none" aria-hidden="true">
    <table  id="table6">
        <colgroup>
            <col style="width: 15%">
            <col style="width: 35%">
            <col style="width: 15%">
            <col style="width: 35%">
        </colgroup>
        <tr>
            <td class="error_message" colspan="2"><p style="display: none;">{{$lan::get('mail_template_required_name')}}</p></td>
        </tr>
        <tr>
            <td class="t6_td1">{{$lan::get('mail_template_name_title')}}<span class="aster">&lowast;</span></td>
            <td colspan="3"><input id="name_mail_template" class="text_l form-group" style="ime-mode:active;" type="text" name="name_mail_template" placeholder="{{ $lan::get('mail_template_required_name') }}"/></td>
        </tr>
        <tr>
            <td id="error_message_name" class="error_message" colspan="2"><p style="display: none;">{{$lan::get('mail_template_error_message_required_name')}}</p></td>
        </tr>
    </table>
</div>

<div id="dialog-confirm_delete"  style="display: none;">
    {{$lan::get('delete_ok')}}
</div>
    
<!-- Work with mail template -->
<style type="text/css">
    /*td {*/
        /*cursor: pointer;*/
    /*}*/
    #table tbody tr:hover {
        background-color: #6A90A4;
        color: #fff;
    }

    .selected {
        background-color: #ff8c00 !important;
        color: #FFF;
    }

    .divider{
        width:5px;
        height:auto;
        display:inline-block;
    }
</style>
<script type="text/javascript">
    // Global definition of type_mail.   
    var mail_type = 1;

    // if value is null set mail_type = 1 else mail_type = type
    function getTypeMail(type) {
        mail_type = type;
        // set value for select option
        $("#search_by_type").val(mail_type);
    }
$(function() {
    // highlight when click
    $(document).on("click", "#table tbody td", function () {
        $(this).parent().addClass('selected').siblings().removeClass('selected'); 
    });

    //Create event
    $("#btn_create_list").click(function() {
        $("#name_mail_template").val("");
        $("#mail_template_create_dialog").dialog("open");
    });

    // show dialog create when click button create
    $("#mail_template_create_dialog").dialog({
        width: 550,
        height: 250,
        title: "{{$lan::get('mail_template_main_title_create')}}",
        autoOpen: false,
        dialogClass: "fixed_dialog",
        resizable: false,
        modal: true,
        buttons: [
            {
                text: "OK",
                click: function() {
                    // if name mail template is null -> shoe error
                    if ($("#name_mail_template").val() == "") {
                        $('#table6 td.error_message p').show();
                        $('#error_message_name p').hide();
                        return;
                    }

                    // else hide error message
                    $('#table6 td.error_message p').hide();
                    $('#error_message_name p').hide();

                    // get all value of input when create 
                    var name = $("#name_mail_template").val();                   
                    var mail_type = window.mail_type;
                    console.log (mail_type);
                    var subject= $("#mail_subject").val();
                    var body= tinyMCE.get("mail_description").getContent().replace(/(<p>)*/g, "").replace(/<(\/)?p[^>]*>/g, "");
                    var footer= $("#mail_footer").val();
                    // results = true or false when check unique name
                    var results = checkUniqueName (name);

                    // if results = true -> insert value to database
                    if (results) {
                        $( this ).dialog( "close" );
                        $('#error_message_name p').hide();
                        createMailTemplate(name, mail_type, subject, body, footer);
                    }
                    // else show error message with name is exist
                    else {
                        $('#error_message_name p').show();
                    }
                }
            },
            {
                text: "{{$lan::get('cancel_title')}}",
                click: function() {
                    $('#table6 td.error_message p').hide();
                    $('#error_message_name p').hide();
                    $(this).dialog('close');
                }
            }
        ]
    });

    //Show list mail
    $("#btn_load_list").click(function() {
        var id = $( "#search_by_type option:selected" ).val();

        var mail_type = {!! json_encode($mail_template_type); !!};
        var url = '/school/mailtemplate/search';
        var data = { type_mail: id };
        var message = "{{$lan::get('mail_template_data_empty')}}";
        generateTable (url, data, mail_type, message);
        
    });

    //Show list mail by mail type
    $("#search_by_type").change(function(){
        var type_mail = $(this).val();
        var mail_type = {!! json_encode($mail_template_type); !!};
        var url = '/school/mailtemplate/search';
        var data = { type_mail: type_mail };
        var message = "{{$lan::get('mail_template_data_empty')}}";
        generateTable (url, data, mail_type, message);
    });

    //Setting dialog for select mail
    $("#mail_template_dialog").dialog({
        width: 600,
        height: 500,
        title: "{{$lan::get('mail_template_main_title')}}",
        autoOpen: false,
        dialogClass: "fixed_dialog",
        resizable: false,
        modal: true,
        buttons: [
            {
                text: "OK",
                click: function() {
                    $(this).dialog('close');
                    var id = $('tr.selected td:first input').val();

                    //Clear all field of mail
                    $("#mail_subject").val("");
                    $("#mail_description").text("");
                    tinyMCE.activeEditor.setContent("");
                    $("#mail_footer").val("");

                    getInfoMailTemplate(id);
                    bindTemplateData = function(data){
                        $("#mail_subject").val(data.subject);
                        $("#mail_description").text(data.body);
                        $("#mail_footer").val(data.footer);
                        tinyMCE.activeEditor.setContent(data.body);
                    }

                    $('.table1 tr.selected').removeClass('selected');                        
                }
            },
            {
                text: "{{$lan::get('delete_title')}}",
                click: function() {
                    $( "#dialog-confirm_delete" ).dialog('open');
                }
            },
            {
                text: "{{$lan::get('cancel_title')}}",
                click: function() {
                    $('.table1 tr.selected').removeClass('selected');
                    $(this).dialog('close');
                }
            }
        ]
    });

    // show dialog confirm delete
    $( "#dialog-confirm_delete" ).dialog({
        title: "{{$lan::get('mail_template_delete_main_title')}}",
        autoOpen: false,
        dialogClass: "no-close",
        resizable: false,
        modal: true,
        buttons: {
            "{{$lan::get('delete_title')}}": function() {
                $( this ).dialog( "close" );
                var id = $('tr.selected td:first input').val();
                deleteMailTemplate(id);
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
            }
        }
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('input[name="_token"]').val()
    }
});
</script>