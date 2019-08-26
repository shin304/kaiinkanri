<!-- {{assign var=reqArray value=$request|@array_str|smarty:nodefaults }} -->
<!-- {{foreach from=$reqArray key=name item=value}} -->
<!-- {{if $excludeRegString}} -->
<!-- {{if preg_match($excludeRegString, $name) }} -->
<!-- {{else}} -->
<!-- <input type="hidden" name="{{$name}}" value="{{$value|smarty:nodefaults}}" /> -->
<!-- {{/if}} -->
<!-- {{elseif $includeRegString}} -->
<!-- {{if preg_match($includeRegString, $name) }} -->
<!-- <input type="hidden" name="{{$name}}" value="{{$value|smarty:nodefaults}}" /> -->
<!-- {{/if}} -->
<!-- {{else}} -->
<!-- <input type="hidden" name="{{$name}}" value="{{$value|smarty:nodefaults}}" /> -->
<!-- {{/if}} -->
<!-- {{/foreach}} -->
