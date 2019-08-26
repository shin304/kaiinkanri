<tr class="fee_row">
	<td align="center" class='fee_public'>
		<input type="checkbox" name='_i[fee_list][{{ $fff }}][public_flag]' value="1" @if($fee['public_flag']) checked @endif />
	</td>
	<td class="fee_title">
		{{ $fee['title'] }}
	</td>
	<td class="fee_policy">
		@foreach ($fee['policy_list'] as $ppp=>$policy)<p>{{$policy['title']}}</p>@endforeach
	</td>
	<td class="fee_price">
@if ($fee['itunes_code'])
		@foreach ($_a['itunes_list'] as $option)@if ($option['itunes_code'] == $fee['itunes_code']) {{ $option['title'] }} @endif @endforeach
@else
		@foreach ($_a['itunes_list'] as $option)@if ($loop->first) {{ $option['title'] }} @endif @endforeach
@endif
	</td>
	<td align="right">
		{{ $fee['member_cnt'] }}
	</td>
	<td align="center">
		@if ($fee['register_date']) {{ date('Y-m-d', strtotime($fee['register_date'])) }} @endif
	</td>
	<td align="center">
		@if ($fee['update_date']) {{ date('Y-m-d', strtotime($fee['update_date'])) }} @endif
	</td>
	<td align="center">
		<a class="edit_fee" title="編集" data-toggle="modal" data-target="#fee-box_{{ $fff }}" ><img src="/images{{$_app_path}}iconmonstr-edit-10-icon-16.png" /></a>&nbsp;
@if (!$fee['member_cnt'])
		<a class="delete_fee" title="削除"><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>
@endif
		<div class="modal fade" id="fee-box_{{ $fff }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">有料コース確認</h5>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="hidden" name="_i[fee_list][{{ $fff }}][id]" class="fee_id" value="{{ $fee['id'] }}"/>
							<input type="hidden" name="_i[fee_list][{{ $fff }}][del_flag]" class="del_flag"/>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">有料コース名称<span class="glyphicon glyphicon-asterisk required"></span></label>
							<div class="col-sm-8">
								<input type="text" name="_i[fee_list][{{ $fff }}][title]" value="{{ $fee['title'] }}" class="form-control fee_title" maxlength="255" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">価格帯<span class="glyphicon glyphicon-asterisk required"></span></label>
							<div class="col-sm-4">
@if ($fee['member_cnt'])
								@foreach ($_a['itunes_list'] as $option)@if ($option['itunes_code'] == $fee['itunes_code']) {{ $option['title'] }} @endif @endforeach
@else
								<select name="_i[fee_list][{{ $fff }}][itunes_code]" class="form-control fee_price">
									@foreach ($_a['itunes_list'] as $option)<option value="{{ $option['itunes_code'] }}" @if ($option['itunes_code'] == $fee['itunes_code']) selected @endif>{{ $option['title'] }}</option>@endforeach
								</select>
@endif
							</div>
						</div>

@foreach ($fee['policy_list'] as $ppp=>$policy)
@include('Appmanage.Info._policy_table', ['fff'=>$fff, 'ppp'=>$ppp, 'policy'=>$policy])
@endforeach

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary fee-modal-close" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</td>
</tr>
