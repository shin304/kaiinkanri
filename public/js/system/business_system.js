$(function(){
	$("#btnDelete").click(function(){
		var isChecked = false;
		$( ":checkbox" ).each(function(){
				if(this.checked){
					isChecked = true;
				}
		})
		if(isChecked == true){
			var bool= confirm('削除します。よろしいですか。');
			if(bool){
				$("#content_delete").submit();
			}else{
				return;
			}
		}else{
			alert("少なくとも1種類を選択してください！");
		}
	})
	
})