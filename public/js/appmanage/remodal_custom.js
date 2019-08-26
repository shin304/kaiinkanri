function alert_modal(title, message, ok_disp){
	title	= title || '';
	message	= message || '';
	ok_disp	= ok_disp || '';

	$("#alert_modal_title").html(title);
	$("#alert_modal_msg").html(message);
	if (ok_disp){
		$("#alert_modal_confirm").show();
	} else {
		$("#alert_modal_confirm").hide();
	}
	$('[data-remodal-id=alert_modal]').remodal({ hashTracking: false }).open();
	return false;
}
