<input type="datetime" class="form-control datepicker" data-date-format="Y-MM-DD hh:mm A" name="{{ $row->field }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ date('Y-m-d g:i A', strtotime(old($row->field, $dataTypeContent->{$row->field})))  }}@else{{old($row->field)}}@endif">
