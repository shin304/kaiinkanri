<script type="text/javascript">
$(function(){
	resize_maintable();
	var dateObj = new Date();
	var y = dateObj.getFullYear();
	var m = dateObj.getMonth() + 1;
	var d = dateObj.getDate();
	var yb = "日月火水木金土".charAt(dateObj.getDay());
	document.getElementById("currentDate").innerHTML = y+"年"+m+"月"+d+"日&nbsp;"+yb+"曜日";


	$(window).resize(function() {
		resize_maintable();
	});
	
	$('.text_link').click(function() {
		var link = $(this).attr('href');
		// location.href = link;
        java_post(link);
		return false;
	});

	
});

function common_save_confirm(title, content,action_url,arr_data=null) { 
	$( "#common-dialog-confirm" ).dialog({
        title: title,
        autoOpen: false,
        dialogClass: "no-close",
        resizable: false,
        modal: true,
		width: 330,
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
                if(arr_data) {
                	for(x in arr_data) {
                		$("#action_form input[name='"+x+"']").val(arr_data[x]);
                	}
                }
                // $("#action_form input[name='function']").val("3");
                $("#action_form").attr('action', action_url);
                $("#action_form").submit();
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    $( "#common-dialog-confirm" ).html(content);
	$( "#common-dialog-confirm" ).dialog('open');
    return false;
}

function resize_maintable() {
	var WindowHeight = $(window).innerHeight() - $("#header").outerHeight();
	var wrapperHeight = $("#wrapper").outerHeight();
//	if(WindowHeight > 320 && WindowHeight<wrapperHeight){ //開いた画面が320px以上なら実行
	if(WindowHeight > 320){ //開いた画面が320px以上なら実行
		$('#wrapper').css('height',WindowHeight+'px');
	}
}

function java_post(link) {
	var uri = link.split("?");
	
	// フォームの生成
	var form = document.createElement("form");
	form.setAttribute("action", uri[0]);
	form.setAttribute("method", "post");
	form.style.display = "none";
	document.body.appendChild(form);
    var token = document.createElement("td");
    token.innerHTML  = '{{ csrf_field() }}';
    form.appendChild(token);
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

</script>

<h2 id="content_h2" class="box_border1" style="margin-top: 0px; margin-bottom: 0px; line-height: 20px!important; "><i class="fa fa-home"></i>
	@if ( session()->has('school.login.staff_id') )
		{{session('school.login.staff_name')}}
    @elseif ( session()->has('school.login.coach_id') )
		{{session('school.login.coach_name')}}
	@else
		{{$lan::get('admin_title')}}
    @endif
</h2>

<div id="left_content_box1"  class="box_border1 bgcolor1">
	<p style="margin-bottom: 0px!important; line-height: 30px !important;" class="p_date p20"><i class="fa fa-clock-o"></i><span id="currentDate"></span></p>
	<!-- <p class="p_date p20"><i class="fa fa-clock-o"></i>12月14日 日曜日</p> -->
	<!-- <p class="totag1">請求書締め日</p> -->
</div><!--left_content_box1-->
<div id="left_content_ul">
	<ul>
		@foreach ($menuList as $index=>$menu) 
		<a href="{{$_app_path}}{{$menu['action_url']}}">
		<li style=" margin-right: 0px; line-height: 20px!important; "><i style="margin-right: 5px !important;" class=" @if(!empty($menu['icon_url'])) {{$menu['icon_url']}}  @elseif(!empty($menu['master_icon'])) {{$menu['master_icon']}} @else fa fa-chevron-right @endif "></i>
		    {{$lan::get($menu['menu_name_key'])}}
		</li></a>
			<!-- sub menu -->
			@if (array_key_exists($menu['master_menu_id'],$submenuList))
				@foreach ($submenuList[$menu['master_menu_id']] as $submenu) 
					<li class="submenu"><i class="fa fa-chevron-circle-right"></i>{{$lan::get($submenu['menu_name_key'])}}</li>
				@endforeach
			@endif
		@endforeach
	</ul>
</div><!--left_content_ul-->
<div id="common-dialog-confirm"  style="display: none;">
    
</div>