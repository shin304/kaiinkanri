function checkUniqueName(template_name) {
    var succeed = false;
    $.ajax({
        type:'get',
        async: false,
        url: "/school/mailtemplate/validateName",
        data: {name : template_name},
        dataType : 'json',
        contentType: 'application/json',
        success: function(data) {
            succeed = data;
        },
        error: function(data) {
            console.log('error');
            succeed = false;
        }
    });
    return succeed;
}

function generateTable(url, data, type_mail, message) {
    var type_mail = type_mail;
	$.ajax({
        type:'get',
        url: url,
        data: data,
        success: function(data) {
            var dom = '';
            var info;
            if ($.trim(data)) {
                $.each(data, function(index, val) {
                    info = {
                        id: val.id,
                    };
                    
                    var type_id = val.mail_type;
                    dom += "<tr id='value" + val.id + "'>"+
                            "<td style='display: none;'><input type='hidden' style='display: none;' class='rad_select_mail' data_id= "+ createDataAttrDom(info) +" value="+ createDataAttrDom(info) + "></td>" +
                            "<td style='padding-left: 10px;' class='text_left'>"+ val.name +"</td>" +
                            "<td class='text_left'>"+ type_mail[type_id] +"</td>" +
                        "</tr>";
                })
            }
            else {
                dom += "<tr align='left'><td colspan='3' style='padding-left: 10px;'>" + message + "</td></tr>";
            }

            $("#mail_template_dialog table.table1 tbody").html(dom);
            // console.log (dom);
            $("#mail_template_dialog").dialog("open");
        }
    })
}

var createDataAttrDom = function(attr) {
    var dom = "";
    $.each(attr, function(key, val) {
        // dom += "data_" + key + "='" + ((val == null || val == "NULL" || val == "null") ? '' : val) + "' ";
        dom += "'" + ((val == null || val == "NULL" || val == "null") ? '' : val) + "' ";
    });
    // console.log (dom);
    return dom;
}

function deleteMailTemplate(id) {
	var id = id;
	// alert (id);
	$.ajax({
	    type: "DELETE",
	    url: '/school/mailtemplate/delete?id=' + id,
	    success: function () {
	        $("#value" + id).remove();
            $("#delete_error").hide();
            $("#delete_success").show();
            setTimeout(function(){
                $("#delete_success").hide();
                    }, 3000);
	    },
	    error: function () {
	        $("#delete_error").show();
            $("#delete_success").hide();
            setTimeout(function(){
                $("#delete_error").hide();
                    }, 3000);
	    }
	});
}

function createMailTemplate(name, mail_type, subject, body, footer) {
	$.ajax({
        type:"post",   
        url: '/school/mailtemplate/create',
        data: {name: name,
        	mail_type: mail_type,
            subject: subject,
            body: body,
            footer: footer
            },
        success: function(data) {
            $("#create_error").hide();
            $("#create_success").show();
            setTimeout(function(){
                $("#create_success").hide();
                    }, 3000);
        },
        error: function () {
            $("#create_error").show();
            $("#create_success").hide();
            setTimeout(function(){
                $("#create_error").hide();
                    }, 3000);
        }
    });
}

function getInfoMailTemplate(id) {
	var id = id;
    $.ajax({
        type:"get",   
        url: '/school/mailtemplate/getInfo',
        data: {id: id},
        success: function(data) {
            bindTemplateData(data);
        }
    });
}