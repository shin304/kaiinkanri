<div style="overflow: hidden;">
	<input type="file" class="file-input" style="display: none;" name="_f{{$input}}[{{$link}}]" data-del="0" />
	<input type="text" class="form-control file-name" name="_i{{$input}}[{{$file}}]" value="{{$file_val}}" placeholder="ファイルを選択" style="background: white; cursor: default;" readonly/>
	<a class="file-del" title="削除" ><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>
	<input type="text" class="d-none" name="_i{{$input}}[{{$link}}]" value="{{$link_val}}" />
</div>