var show_url_dialog = function(url, option) {
	$("body").append('<div id="custom_dialog_area" style="display:none;"><div id="custom_dialog"></div></div>');
	//$("#loading").show();

	$("#custom_dialog").load(url, null, function() {
		
		$("#custom_dialog_area").dialog({
			autoOpen: true,
			modal: true,
			title: option.title,
			height: option.height,
			width: option.width,
			position: option.position,
			buttons: {
				"閉じる": function(){
					$("#custom_dialog_area").dialog("close");
				}
			},
			open: function(){
				if (option.open_callback) {
					option.open_callback();
				}
			},
			close: function() {
				$("#custom_dialog_area").dialog("destroy");
				$("#custom_dialog_area").remove();
			}
		});
		
		//$("#loading").hide();
	});
}
