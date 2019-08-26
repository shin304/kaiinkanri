<!-- start of body header -->
<script type="text/javascript">
var show_url_dialog2 = function(url, option) {
}
function jsonParser1(data, key){
	var split = data.split(',');
	for( var idx = 0; idx < split.length; idx++){
		if( split[idx].indexOf(key) != -1 ){
			// 蟄伜惠
			var temp_str = split[idx].split(':');
			var rep_str = temp_str[1].replace(/"/g, "");
			return rep_str.trim();
		}
	}
}
function jsonParser2(data, key){
	var split = data.split(',');
	for( var idx = 0; idx < split.length; idx++){
		if( split[idx].indexOf(key) != -1 ){
			// 蟄伜惠
			var temp_str = split[idx].split(':');
			var rep_str = temp_str[1].replace(/"/g, "");
			var rep_str = rep_str.replace(/}/g, "");
			return rep_str.trim();
		}
	}
}
//Toran add function check input only number , not half width or full width
// 1. global variable  (temporary define)
var _return_value = "";

// input value check
function check_numtype(obj){

    // 2. variable define
    var txt_obj = $(obj).val();
    var text_length = txt_obj.length;

    // 3. input key check(numeric)
    if(txt_obj.match(/^[0-9\-]+$/)){
        _return_value = txt_obj;
    }else{
        // 3.1 input key not numeric
        if(text_length == 0){
            $(obj).val("");
            _return_value = "";
        }else{
            $(obj).val(_return_value);
        }
    }
}

</script>

<header id="header">

	<div style="float: left; width: 100%;">
        <h1 style="margin-top: 0px; margin-bottom: 0px; font-weight: 200; padding-top: 0px; !important;"><img src="/img{{$_app_path}}rakurakulogo.png" width="100px"></h1>
        <div style="position: absolute;top: 4%;left: 10%;">
            <label style="font-size: 28px;font-weight: bold!important;">{{session('school.login.name')}}</label>
        </div>
        <div style="float: right; margin-top: 30px;" class="top_btn">
            <ul>
                <a target="_blank" href="{{request()->root()}}/online_manual/homegamen.html">
                {{--<a href="{{$_app_path}}/manual_doc/menu">--}}
                    <li style="color: #595959; font-weight: normal; font-size: 14px; border-radius: 5px;"><i class="fa fa-book"></i>{{$lan::get('manual_document_title')}}</li>
                </a>
            </ul>
        </div>
	</div>
    <button onclick="$('.dynamic_menu').toggleClass('dynamic_menu_hide')"  class="btn btn-default">
        <i class="fa fa-bars" aria-hidden="true"></i> メニュー
    </button>
</header>

<!-- end of body header -->