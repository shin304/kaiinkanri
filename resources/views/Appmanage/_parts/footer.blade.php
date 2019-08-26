<div class="col-md-12" style="margin-top: 10px;">
<div class="well well-sm" style="text-align: center;">
&copy;2014 ASTO System Inc.
</div>
</div>

<!-- pager -->
{{-- table class="tablepaginate" theadを忘れずに div class="pager_box_base"で挿入場所指定 --}}
<script src='/js{{$_app_path}}paginate.js'></script>
<script>
function paginate_init() {
	if ($('.tablepaginate').length){
		$('.tablepaginate').paginate({'rows': '20', 'showIfLess': true});
	}
	return false;
}
</script>

<!-- remodal -->
{{-- return alert_modal('title', 'message', 'OKボタン表示:1'); --}}
<div class="remodal" data-remodal-id="alert_modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking: false">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<div>
		<p class="mb20" id="alert_modal_title" style="font-size: 24px; font-weight: 500;">こちらで登録しますか？</p>
		<p class="mb20" id="alert_modal_msg" >よろしければOKボタンを押して下さい。</p>
	</div>
	<button id="alert_modal_cancel" data-remodal-action="cancel" class="remodal-cancel">キャンセル</button>
	<button id="alert_modal_confirm" data-remodal-action="close" class="remodal-confirm" onclick="return alert_modal_confirm();">OK</button>
</div>

<div class="remodal" data-remodal-id="alert_logout" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking: false">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<div>
		<p class="mb20" id="alert_modal_title" style="font-size: 24px; font-weight: 500;">ログアウトします。よろしいでしょうか？</p>
		<p class="mb20" id="alert_modal_msg" >よろしければOKボタンを押して下さい。</p>
	</div>
	<button id="alert_modal_cancel" data-remodal-action="cancel" class="remodal-cancel">キャンセル</button>
	<button id="alert_modal_confirm" data-remodal-action="close" class="remodal-confirm" onclick="return java_post('{{$_app_path}}logout');">OK</button>
</div>
