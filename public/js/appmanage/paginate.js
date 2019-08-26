var nows = 0;
var numPages = 0;

(function ($) {

    $.fn.paginate = function (options) {
    	var posi = "both";
    	if ($('.pager_box_base').length) posi = "class";
    	
        //Default options
        var settings = $.extend({
            rows: 20,
            position: posi,
            jqueryui: false,
            showIfLess: false
        }, options);

        $(this).each(function () {
            var currentPage = nows;
            var rowPerPage = settings.rows;
            var table = $(this);
            table.bind('pageTable', function () {
                table.children('tbody').children('tr').hide().slice(currentPage * rowPerPage, (currentPage + 1) * rowPerPage).show();
            });
            table.trigger('pageTable');
            var numRows = table.children('tbody').children('tr').length;
            numPages = Math.ceil(numRows / rowPerPage);
            var pager = $('<ul class="pagination pagination-sm" style="margin: 0px;"></ul>');

            //Check ui theming====================================================================
            var activeclass = settings.jqueryui ? "ui-state-active" : "active";
            var defaultclass = settings.jqueryui ? "ui-state-default" : "number";
            
            // ページ数
            if (numPages != 1) {
	            for (var page = 0; page < numPages; page++) {
	            	var disp = ' style="display: none;" ';            		            		
	            	if (nows < 5 && page < 10){
	            		disp = '';
	            	}
	            	else if (numPages - nows < 6 && numPages - page < 11){
	            		disp = '';
	            	}
	            	else if (nows -5 < page && page < nows +6){
	            		disp = '';
	            	}
	            	
	            	var currentclass = (nows == page)? activeclass:defaultclass;
	            	var cursorClass = (nows == page)? "active":"";
	            	
	            	var iii = $('<li class="' + cursorClass + '" onclick="pagination_page(' + page + ', ' + numPages + '); return false;" ' + disp + ' ></li>');
	                $('<a class="' + currentclass + '"></a>').text(page + 1).bind('click', {
	                    newPage: page
	                }, function (event) {
	                    currentPage = event.data['newPage'];
	                    table.trigger('pageTable');
	                    //$(this).addClass(activeclass).siblings().removeClass(activeclass);
	                }).appendTo(iii).addClass('clickable');
	                pager = pager.append(iii);                	
	            }
	        }
        
            if (nows != 0){
	            /*/ < 前へ
	            page = nows -1;
	        	if (page < 0) page = 0;
	        	var iii = $('<li class="cursor_pointer" onclick="pagination_page(' + page + '); return false;" ></li>');
	            $('<a class="' + defaultclass + '"></a>').text('前へ').bind('click', {
	                newPage: page 
	            }, function (event) {
	                currentPage = event.data['newPage'];
	                table.trigger('pageTable');
	            }).appendTo(iii).addClass('clickable');
	            pager = pager.prepend(iii);
	            //*/
	            // < 始め
	            var page = 0;
	        	var iii = $('<li class="cursor_pointer" onclick="pagination_page(' + page + '); return false;" ></li>');
	            $('<a class="' + defaultclass + '"></a>').text('<').bind('click', {
	                newPage: page
	            }, function (event) {
	                currentPage = event.data['newPage'];
	                table.trigger('pageTable');
	            }).appendTo(iii).addClass('clickable');
	            pager = pager.prepend(iii);                	
            }
            
            if (nows +1 < numPages){
	            /*/ 次へ >
	            page = nows +1;
	        	if (page >= numPages) page = numPages -1;
	        	var iii = $('<li class="cursor_pointer" onclick="pagination_page(' + page + '); return false;" ></li>');
	            $('<a class="' + defaultclass + '"></a>').text('次へ▶').bind('click', {
	                newPage: page
	            }, function (event) {
	                currentPage = event.data['newPage'];
	                table.trigger('pageTable');
	            }).appendTo(iii).addClass('clickable');
	            pager = pager.append(iii);
	            //*/
	            // 最後 >
	            page = numPages -1;
	        	var iii = $('<li class="cursor_pointer" onclick="pagination_page(' + page + '); return false;" ></li>');
	            $('<a class="' + defaultclass + '"></a>').text('>').bind('click', {
	                newPage: page
	            }, function (event) {
	                currentPage = event.data['newPage'];
	                table.trigger('pageTable');
	            }).appendTo(iii).addClass('clickable');
	            pager = pager.append(iii);                	
            }
            
            //件数
        	var iii = $('<li class="disabled"></li>');
            $('<a class="' + defaultclass + '"></a>').text('検索結果 : '+$('#pager_box_cnt').val()+' 件').appendTo(iii);
            pager = pager.prepend(iii);                	
            
			pager = $('<nav class="pager_box"></nav>').append(pager);

            //Add pager===========================================================================
            if (settings.showIfLess) {

                if (settings.position == "class") {
        			$('.pager_box_base').append(pager).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
                else if (settings.position == "top") {
                	pager.insertBefore(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
                else if (settings.position == "bottom") {
                	pager.insertAfter(table).find('span.' + defaultclass + ':first').addClass(activeclass);                	
                }
                else {
                	pager.clone(true).insertBefore(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                	pager.clone(true).insertAfter(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
            }
            else if (rowPerPage < numRows) {
                if (settings.position == "class") {
        			$('.pager_box_base').append(pager).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
                else if (settings.position == "top") {
                	pager.insertBefore(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
                else if (settings.position == "bottom") {
                	pager.insertAfter(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
                else {
                	pager.clone(true).insertBefore(table).find('span.' + defaultclass + ':first').addClass(activeclass);
        			pager.clone(true).insertAfter(table).find('span.' + defaultclass + ':first').addClass(activeclass);
                }
            }


        });

        return this;
    }


})(jQuery);

// はじめに
$(document).ready(function() {
	paginate_init();
});

// ページを指定して初期化
function pagination_page(nows_page) {
    nows = nows_page;
	$('.pager_box').remove();
	paginate_init();
}

