<div class="form-group">
	<label class="col-sm-3 control-label">タイトル<span class="glyphicon glyphicon-asterisk required"></span></label>
	<div class="col-sm-8">
		<input type="hidden" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][id]" value="{{ $question['id'] }}" />
		<input type="text" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][title]" value="{{ $question['title'] }}" class="form-control question_title" maxlength="255" />
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label">形式<span class="glyphicon glyphicon-asterisk required"></span></label>
	<div class="col-sm-4">
		<select name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][question_regi_type]" class="form-control question_regi_type" >
			<option value="1" @if ($question['question_regi_type'] != 2) selected @endif>テキスト</option>
			<option value="2" @if ($question['question_regi_type'] == 2) selected @endif>PDF</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label">問題<span class="glyphicon glyphicon-asterisk required"></span></label>
	<div class="col-sm-8">
		<div class="text @if ($question['question_regi_type'] == 2) d-none @endif">
			<textarea name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][question_text]" class="form-control question_text" placeholder="問題のテキスト" rows="3">{{ $question['question_text'] }}</textarea>
			<div style="overflow: hidden; margin-top: 10px;">
				<label class="control-label" style="float: left; font-weight: normal; margin-right: 10px; ">音声ファイル :</label>
				<div style="float: left; width:80%;">
					@include('Appmanage._parts.input_file', ['input'=>"[chapter_list][{$ccc}][question_list][{$qqq}]", 'file'=>"audio_file_name", 'file_val'=>$question['audio_file_name'], 'link'=>"audio_file", 'link_val'=>$question['audio_file']])
				</div>
			</div>
		</div>
		
		<div class="file @if ($question['question_regi_type'] != 2) d-none @endif question_file">
			@include('Appmanage._parts.input_file', ['input'=>"[chapter_list][{$ccc}][question_list][{$qqq}]", 'file'=>"question_file_name", 'file_val'=>$question['question_file_name'], 'link'=>"question_file", 'link_val'=>$question['question_file']])
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label">解説<span class="glyphicon glyphicon-asterisk required"></span></label>
	<div class="col-sm-8">
		<div class="text @if ($question['question_regi_type'] == 2) d-none @endif">
			<textarea name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][description_text]" class="form-control" placeholder="問題のテキスト" rows="3">{{ $question['description_text'] }}</textarea>
		</div>
		
		<div class="file @if ($question['question_regi_type'] != 2) d-none @endif">
			@include('Appmanage._parts.input_file', ['input'=>"[chapter_list][{$ccc}][question_list][{$qqq}]", 'file'=>"description_file_name", 'file_val'=>$question['description_file_name'], 'link'=>"description_file", 'link_val'=>$question['description_file']])
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label">選択肢<span class="glyphicon glyphicon-asterisk required"></span></label>
	<div class="col-sm-8">
		<div class="text @if ($question['question_regi_type'] == 2) d-none @endif">
			<textarea name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][choices_text]" class="form-control" placeholder="選択肢説明のテキスト" rows="3">{{ $question['choices_text'] }}</textarea>
			<div style="overflow: hidden; margin: 10px 0px; ">
				<label class="control-label" style="float: left; font-weight: normal; margin-right: 10px; ">音声ファイル :</label>
				<div style="float: left; width:80%;">
					@include('Appmanage._parts.input_file', ['input'=>"[chapter_list][{$ccc}][question_list][{$qqq}]", 'file'=>"choices_file_name", 'file_val'=>$question['choices_file_name'], 'link'=>"choices_file", 'link_val'=>$question['choices_file']])
				</div>
			</div>
		</div>
		
		<div>
			<table class="table-bordered choice-table">
				<thead>
					<tr>
						<th style="width: 10%;">正解</th>
						<th style="width: 10%;">記号</th>
						<th class="word @if ($question['question_regi_type'] == 2) v-hidden @endif" style="width: 30%;">文言</th>
						<th class="word @if ($question['question_regi_type'] == 2) v-hidden @endif" style="width: 50%;">ファイル</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($question['choice_list'] as $choice)
					<tr>
						<td align="center">
							<input type="hidden" name="_i[chapter_list][{{$ccc}}][question_list][{{$qqq}}][choice_list][{{$loop->index}}][id]" value="{{ $choice['id'] }}" />
							<input type="radio" class="choice_true" name="_radio_{{$ccc}}_{{$qqq}}" value="{{$loop->index}}" @if ($choice['choice_true']) checked @endif />
							<input type="hidden" name="_i[chapter_list][{{$ccc}}][question_list][{{$qqq}}][choice_list][{{$loop->index}}][choice_true]" value="{{ $choice['choice_true'] }}" />
						</td>
						<td><input type="text" name="_i[chapter_list][{{$ccc}}][question_list][{{$qqq}}][choice_list][{{$loop->index}}][choice_mark]" value="{{ $choice['choice_mark'] }}" class="quest-cell choice_mark"></td>
						<td class="word @if ($question['question_regi_type'] == 2) v-hidden @endif"><input type="text" name="_i[chapter_list][{{$ccc}}][question_list][{{$qqq}}][choice_list][{{$loop->index}}][choice_word]" value="{{ $choice['choice_word'] }}" class="quest-cell choice_word"></td>
						<td class="word @if ($question['question_regi_type'] == 2) v-hidden @endif" style="padding: 4px;">
							@include('Appmanage._parts.input_file', ['input'=>"[chapter_list][{$ccc}][question_list][{$qqq}][choice_list][{$loop->index}]", 'file'=>"choice_file_name", 'file_val'=>$choice['choice_file_name'], 'link'=>"choice_file", 'link_val'=>$choice['choice_file']])
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div style="text-align: left; padding: 4px;">
				<span class="my-notes">*記号が空欄の選択肢は表示されません。</span>
			</div>
		</div>
	</div>
</div>
