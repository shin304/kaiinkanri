<script type="text/javascript">
    function common_confirm(title, content, arr_data=null) {
        $( "#common-dialog-confirm" ).dialog({
            title: title,
            autoOpen: false,
            width: "auto",
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "OK": function() {
                    $( this ).dialog( "close" );
                    if(arr_data) {
                        for(x in arr_data) {
                            $("#action_form input[name='"+x+"']").val(arr_data[x]);
                        }
                    }
                    $("#action_form").submit();
                    return false;
                },
                "キャンセル": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $( "#common-dialog-confirm" ).html(content);
        $( "#common-dialog-confirm" ).dialog('open');
        return false;
    }
</script>
<div id="common-dialog-confirm" style="display: none;"></div>