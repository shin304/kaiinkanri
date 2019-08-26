<div class="alert alert-success alert-dismissable chapter-box"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<div class="form-group">
		<label class="col-sm-1 control-label chapter-num">@if (is_numeric($ccc)){{ $ccc+1 }}@else{{ $ccc }}@endif</label>
		<input type="hidden" name="_i[chapter_list][{{ $ccc }}][id]" value="{{ $chapter['id'] }}"/>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">タイトル<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-8">
			<input type="text" name="_i[chapter_list][{{ $ccc }}][title]" value="{{ $chapter['title'] }}" class="form-control" maxlength="255" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">サブタイトル<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-8">
			<input type="text" name="_i[chapter_list][{{ $ccc }}][subtitle]" value="{{ $chapter['subtitle'] }}" class="form-control" maxlength="255" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">教科<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-4">
			<select name="_i[chapter_list][{{ $ccc }}][subject_id]" class="form-control">
			@foreach ($_a['subject_list'] as $option)<option value="{{ $option['subject_id'] }}" @if ($option['subject_id'] == $chapter['subject_id']) selected @endif>{{ $option['name'] }}</option>@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">満点<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-2">
			<input type="number" name="_i[chapter_list][{{ $ccc }}][full_score]" value="{{ $chapter['full_score'] }}" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">試験時間<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-2">
			<input type="number" name="_i[chapter_list][{{ $ccc }}][exam_time]" value="{{ $chapter['exam_time'] }}" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">設問<span class="glyphicon glyphicon-asterisk required"></span></label>
		<div class="col-sm-9">
			<table class="table-bordered table-striped quest-table" data-wc="{{ $ccc }}" data-wq="{{ count($chapter['question_list']) }}">
				<thead>
					<tr>
						<th style="width:  8%;">順番</th>
						<th style="width: 12%;">番号</th>
						<th style="width: 12%;">タイトル</th>
						<th style="width:auto;">問題</th>
						<th style="width: 14%;">選択記号</th>
						<th style="width:  8%;">正解</th>
						<th style="width: 10%;"></th>
					</tr>
				</thead>
				<tbody>
				@foreach($chapter['question_list'] as $question)
					@if (is_numeric($ccc)) <?php $qqq = $loop->index; ?> @endif
					<tr>
						<td><input type="text" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][sequence_no]" value="{{ $question['sequence_no'] }}" class="quest-cell edit-cell seq_no" /></td>
						<td><input type="text" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][tags]" value="{{ $question['tags'] }}" class="quest-cell edit-cell tags" /></td>
						<td><input type="text" value="{{ $question['title'] }}" class="quest-cell disp_title read-cell" readonly/></td>
						<td><input type="text" value="@if ($question['question_regi_type'] == 2) {{ $question['question_file_name'] }} @else {{$question['question_text'] }} @endif" class="quest-cell disp_text read-cell" readonly/></td>
						<td><input type="text" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][choices]" value="{{ $question['choices'] }}" class="quest-cell choices read-cell" readonly/></td>
						<td><input type="text" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][c_answer]" value="{{ $question['c_answer'] }}" class="quest-cell c_answer read-cell" readonly/></td>
						<td align="center">
							<input type="hidden" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][id]" value="{{ $question['id'] }}" />
							<input type="hidden" name="_i[chapter_list][{{ $ccc }}][question_list][{{ $qqq }}][sentence_id]" value="{{ $question['sentence_id'] }}" />
							<a class="edit_question" title="編集" data-toggle="modal" data-target="#quest-box_{{ $ccc }}_{{ $qqq }}" ><img src="/images{{$_app_path}}iconmonstr-edit-10-icon-16.png" /></a>&nbsp;
							<a class="delete_question" title="削除"><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>

							<div class="modal fade" id="quest-box_{{ $ccc }}_{{ $qqq }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLongTitle">設問確認</h5>
										</div>
										<div class="modal-body">
											@include('Appmanage.Workbook._question_table', ['ccc'=>$ccc, 'qqq'=>$qqq, 'question'=>$question])
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary question-modal-close" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div style="padding: 10px;">
				<a class="add_question"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">設問の追加</span></a>　
			</div>
		</div>
	</div>
</div>
