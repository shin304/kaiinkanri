$(function() {
	// set list sequence 
	// seq_lst: [1,2,3,7,...] #master_menu_id
	var seq_lst=[];
	$('#master_menu_side li').each(function() {
		seq_lst.push(parseInt($(this).attr('idx')));
	})
	
	$('.menu_item').change(function() { 
		//Ex: li: <li idx="1"><input type="checkbox" class="menu_item"...</li>
		var li = $(this).parent().clone();

		var idx = ($(this).parent().attr('idx'));
		li.children().attr('name', 'menu_list['+idx+']');
		li.children().attr('type', 'hidden');
		li.children().first().attr('disabled', false);

		if ($(this).is(":checked")) {
			// append for the first time OR top list
			if ($('#menu_side li').length == 0 || idx == 0) {
				$('#menu_side').prepend(li);
			} else {
				
				//append to next position
				var nodeIndex = seq_lst.indexOf(parseInt(idx)); flag = false;
				$.each(seq_lst.slice( 0,nodeIndex ).reverse(), function(i, val){
			   		objPre = '#menu_side li[idx='+val+']';
			   		if ($(objPre).length != 0) {

						$(li).insertAfter(objPre);
						flag=true;return false; // return false:break each loop; flag=true: check is appended or not
					}
				}); 
				
				// case cannot find next position, then append to top
				if (!flag) {
					$('#menu_side').prepend(li);
				}
			}
			//display view-edit list
			$('#view_edit li[idx='+idx+']').addClass('displayLi').removeClass('hideLi');
			$('#view_edit li[idx='+idx+'] input[class=chkViewable]').attr('name', 'viewable_list['+idx+']');// allow pass to server
			$('#view_edit li[idx='+idx+'] input[class=chkEditable]').attr('name', 'editable_list['+idx+']');// allow pass to server
			// $('#view_edit li[idx='+idx+'] input[class=chkViewable]').attr('disabled', false);
			// $('#view_edit li[idx='+idx+'] input[class=chkEditable]').attr('disabled', false);
			// $('#view_edit li[idx='+idx+'] select').attr('disabled', false);// allow pass to server
			
			//display up-down button
			$('#up_down li[idx='+idx+']').addClass('displayLi').removeClass('hideLi');

			//reload position
			var idxprev = li.prev().attr('idx');
			if (idxprev != undefined) {
				$('#view_edit li[idx='+idx+']').insertAfter($('#view_edit li[idx='+idxprev+']'));
				$('#up_down li[idx='+idx+']').insertAfter($('#up_down li[idx='+idxprev+']'));
			} 
		//unselect menu
		} else {
			$('#menu_side li[idx='+idx+']').remove();
			//remove view-edit list
			$('#view_edit li[idx='+idx+']').addClass('hideLi').removeClass('displayLi');
			$('#view_edit li[idx='+idx+'] input[class=chkViewable]').attr('name', '');// prevent pass to server
			$('#view_edit li[idx='+idx+'] input[class=chkEditable]').attr('name', '');// prevent pass to server
			// $('#view_edit li[idx='+idx+'] select').attr('disabled', true); // prevent pass to server
			//remove up-down button
			$('#up_down li[idx='+idx+']').addClass('hideLi').removeClass('displayLi');
		}
		
		//review list to hide first and last button
		reviewUpDown();

		//constraint logic select menu-submenu
		// check submenu => check parent menu
		if (li.hasClass('submenu')){
			if ($(this).is(":checked")) {
				$('#master_menu_side li[idx='+idx+']').prevAll(":not(.submenu)").children().each(function(i, value){
					if (!$(value).is(":checked")) {
						$(value).prop('checked',true);
						$(value).change();
					}
					return false;
				});
			}
		// uncheck menu => uncheck submenu
		} else {
			if (!$(this).is(":checked")) {
				$('#master_menu_side li[idx='+idx+']').nextUntil(":not(.submenu)").children(':checked').each(function(i, value){
					$(value).attr('checked',false);
					$(value).change();
				});
			}
		}
	});

	//call 固定 menu
	$('.menu_item:checked').change();
	// $('.menu_item:checked').attr('disabled', true);
	//applay default_menu/template
	// @foreach ( $defaultMenuList as $index => $menu) 
	// 	var master_menu_id = {{$menu['master_menu_id']}};
	// 	$('.menu_item[value='+master_menu_id+']').attr('checked', true);
	// 	$('.menu_item[value='+master_menu_id+']').change();
	// @endforeach

	//constraint logic select view-edit
	$('.chkViewable, .chkEditable').change(function() {
		if ($(this).is(":checked")) {
			//check edit => check view
			if ($(this).hasClass('chkEditable')) {$(this).prev().prop('checked',true);}
			
		} else {
			//uncheck view => uncheck edit
			if ($(this).hasClass('chkViewable')) {$(this).next().attr('checked',false);}
		}
	});
	loadPositionBySeqNo(seq_lst);
});
function stepDown(idx, next_id) { 
	var objMenu = $('#menu_side li[idx='+idx+']');
	var objNext = $('#menu_side li[idx='+idx+']').nextAll(":not(.submenu)").first();

	if (next_id) {
		objNext = $('#menu_side li[idx='+next_id+']');
	} 
	//append objNext has submenu
	if (objNext.nextUntil(":not(.submenu)").last().length > 0) {
		objNext = objNext.nextUntil(":not(.submenu)").last();
	}
	var idnext 	= objNext.attr('idx');

	//append objMenu has submenu 
	$(objMenu.nextUntil(":not(.submenu)").get().reverse()).each(function (i, value) {
		subidx = $(value).attr('idx');
    	
    	//append submenu to next li
    	$(value).insertAfter(objNext);
    	$('ul#view_edit li[idx='+subidx+']').insertAfter($('ul#view_edit li[idx='+idnext+']'));
    	$('ul#up_down li[idx='+subidx+']').insertAfter($('ul#up_down li[idx='+idnext+']'));
	});
	
	objMenu.insertAfter(objNext);
	$('ul#view_edit li[idx='+idx+']').insertAfter($('ul#view_edit li[idx='+idnext+']'));
	$('ul#up_down li[idx='+idx+']').insertAfter($('ul#up_down li[idx='+idnext+']'));
	
	//review list to hide first and last button
	reviewUpDown();
}
function stepDownSub(idx) {
	var objMenu = $('#menu_side li[idx='+idx+']');
	var objEdit = $('#view_edit li[idx='+idx+']');
	var objUpDown = $('#up_down li[idx='+idx+']');
	objMenu.insertAfter(objMenu.next(".submenu"));
	objEdit.insertAfter(objEdit.next(".submenu.displayLi"));
	objUpDown.insertAfter(objUpDown.next(".submenu.displayLi"));
	reviewUpDown();
}
function stepUp(idx) {
	var objMenu = $('#menu_side li[idx='+idx+']');
	var objPrev = $('#menu_side li[idx='+idx+']').prevAll(":not(.submenu)").first();
	var idprev 	= objPrev.attr('idx');
	var subidx	;
	//append objMenu has submenu 
	$(objMenu.nextUntil(":not(.submenu)").get().reverse()).each(function (i, value) {
		subidx = $(value).attr('idx');
    	
    	//append submenu to prev li
    	$(value).insertBefore($('ul#menu_side li[idx='+idprev+']'));
    	$('ul#view_edit li[idx='+subidx+']').insertBefore($('ul#view_edit li[idx='+idprev+']'));
    	$('ul#up_down li[idx='+subidx+']').insertBefore($('ul#up_down li[idx='+idprev+']'));
    	idprev = subidx;
	});
	
	objMenu.insertBefore($('ul#menu_side li[idx='+idprev+']'));
	$('ul#view_edit li[idx='+idx+']').insertBefore($('ul#view_edit li[idx='+idprev+']'));
	$('ul#up_down li[idx='+idx+']').insertBefore($('ul#up_down li[idx='+idprev+']'));

	//review list to hide first and last button
	reviewUpDown();
}
function stepUpSub(idx) {
	var objMenu = $('#menu_side li[idx='+idx+']');
	var objEdit = $('#view_edit li[idx='+idx+']');
	var objUpDown = $('#up_down li[idx='+idx+']');
	objMenu.insertBefore(objMenu.prev(".submenu"));
	objEdit.insertBefore(objEdit.prevAll(".submenu.displayLi").first());
	objUpDown.insertBefore(objUpDown.prevAll(".submenu.displayLi").first());
	reviewUpDown();
}
function reviewUpDown() {
	//reverse all display
	$('#up_down li[class=displayLi] button').css('display', 'inline-block');

	//check first and last button
	$('#menu_side li:not(.submenu)').each(function(index) {
		var idx = $(this).attr('idx');
		// first element
		if (index == 0) {
			$('#up_down li[idx='+idx+'] #btnUp').hide();
		//last element
		} else if (index == $('#menu_side li:not(.submenu)').length-1) {
			$('#up_down li[idx='+idx+'] #btnDown').hide();
		} else {
			$('#up_down li[idx='+idx+'] #btnUp').show();
			$('#up_down li[idx='+idx+'] #btnDown').show();
		}

	});

	//submenu
	$('#up_down .submenu button, #up_downli.displayLi button').css('display', 'inline-block');
	$('#menu_side li.submenu').each(function(index) {
		var idx = $(this).attr('idx');
		// first element
		if (index == 0) {
			$('#up_down li[idx='+idx+'] #btnUp').hide();
		//last element
		} else if (index == $('#menu_side li.submenu').length-1) {
			$('#up_down li[idx='+idx+'] #btnDown').hide();
		} else {
			$('#up_down li[idx='+idx+'] #btnUp').show();
			$('#up_down li[idx='+idx+'] #btnDown').show();
		}

	});
}
