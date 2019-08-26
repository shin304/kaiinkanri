$(function() {
	$('.datetimepicker').datetimepicker({
		lang:'ja',
		i18n:{
			ja:{
				months:['１月','２月','３月','４月','５月','６月','７月','８月','９月','１０月','１１月','１２月'],
				dayOfWeek:["日", "月", "火", "水","木", "金", "土"]
			}
		},
		format:'Y-m-d H:i:s',
		step:60,
	});

	$('.datepicker').datetimepicker({
		lang:'ja',
		i18n:{
			ja:{
				months:['１月','２月','３月','４月','５月','６月','７月','８月','９月','１０月','１１月','１２月'],
				dayOfWeek:["日", "月", "火", "水","木", "金", "土"]
			}
		},
		format:'Y-m-d',
		timepicker:false,
		closeOnDateSelect:true,
	});

	$("#link_search").click(function() {
		$("#search_area").toggle();
	});

	$("#btn_search").click(function() {
		$("#search_form").submit();
	});

	$("#btn_reset").click(function() {
		$("#search_form input[type='text'], #search_form input[type='number'], #search_form select").each(function() {
			$(this).val('');
		});
		$("#search_form input[type='radio']").each(function() {
			if ($(this).val()!=0) {
				$(this).prop('checked', false);
			} else {
				$(this).prop('checked', true);
			}
		});
	});

	$("body").on('change', '.file-input', function() {
		$(this).next().val($(this).prop('files')[0].name);
		return false;
	});

	$("body").on('click', '.file-del', function() {
		$(this).prev().prev().replaceWith($(this).prev().prev().clone());
		$(this).prev().val('');
		$(this).next().val('');
		return false;
	});

	$("body").on('focus', '.file-name', function() {
		$(this).prev().click();
		return false;
	});

});

function java_post(link, target) {
	var uri = [link , ""];
	if (~link.indexOf("?")){
		uri = link.split("?");
	}

	// フォームの生成
	var form = document.createElement("form");
	form.setAttribute("action", uri[0]);
	form.setAttribute("method", "post");
	form.setAttribute("target", target||"");
	form.style.display = "none";
	document.body.appendChild(form);

	// csrf
	var token = $('meta[name="csrf-token"]').attr('content');
	var input_s = document.createElement('input');
	input_s.setAttribute('type', 'hidden');
	input_s.setAttribute('name', '_token');
	input_s.setAttribute('value', token);
	form.appendChild(input_s);

	if (uri[1]){
		// パラメータの設定
		var params = uri[1].split("&");
		$.each(params,function(idx,param){
			var value = param.split("=");
			var input = document.createElement('input');
			input.setAttribute('type', 'hidden');
			input.setAttribute('name', value[0]);
			input.setAttribute('value', value[1]);
			form.appendChild(input);
		});
	}

	form.submit();
	return false;
}

//確認ダイアログの表示(JQuery)
function alert_box(strComment, callback, btn, strTitle, height) {
    strTitle = strTitle || 'いくてるアプリ';
    strComment = strComment || 'よろしいですか？';
    btn = btn || '2';
    if (!height) {
    	height = "auto";
	}

    if (!callback){
    	btn = 1;
    }

	// ダイアログのメッセージを設定
	$( "#alert_box" ).html( "<div style='padding-top: .5em;'>"+strComment+"</div>" );

	// ダイアログを作成
	if (btn == 1){
		$( "#alert_box" ).dialog({
			modal: true,
			title: strTitle,
			open: function(event, ui){ $(".ui-dialog-titlebar-close").hide(); },
			width: '410',
			height: height,
	        dialogClass: 'alert_box',
			buttons: [
						{
							text: "OK",
							click: function() {
								$( this ).dialog( "close" );
								if (typeof callback === 'function') callback();
								return false;
							},
							class:"alert_box_btn"
						}
			]
		});
	}else{
		$( "#alert_box" ).dialog({
			modal: true,
			title: strTitle,
			open: function(event, ui){ $(".ui-dialog-titlebar-close").hide(); $(':focus').blur();},
			width: '410',
	        dialogClass: 'alert_box',
			buttons: [
				{
					text: "OK",
					click: function() {
						$( this ).dialog( "close" );
						if (typeof callback === 'function') callback();
						return false;
					},
					class:"alert_box_btn"
				},
				{
					text: "キャンセル",
					click: function() {
						$( this ).dialog( "close" );
						return false;
					},
					class:"alert_box_btn"
				}
			]
		});
	}
	return false;
}
