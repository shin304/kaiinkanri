						<div class="alert alert-success alert-dismissable policy-box">
{{--
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
--}}
							<div class="form-group">
{{--
								<label class="col-sm-1 control-label chapter-num">@if (is_numeric($ppp))規約{{ $ppp+1 }}@else{{ $ppp }}@endif</label>
--}}
								<input type="hidden" name="_i[fee_list][{{ $fff }}][policy_list][{{ $ppp }}][id]" value="{{ $policy['id'] }}"/>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">規約名称<span class="glyphicon glyphicon-asterisk required"></span></label>
								<div class="col-sm-8">
									<input type="text" name="_i[fee_list][{{ $fff }}][policy_list][{{ $ppp }}][title]" value="{{ $policy['title'] }}" class="form-control policy_title" maxlength="255" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">規約文<span class="glyphicon glyphicon-asterisk required"></span></label>
								<div class="col-sm-8">
									<textarea name="_i[fee_list][{{ $fff }}][policy_list][{{ $ppp }}][policy]" class="form-control" rows="5">{{ $policy['policy'] }}</textarea>
								</div>
							</div>
						</div>
