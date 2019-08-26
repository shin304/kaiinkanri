@if ($breadcrumbs) 
@php
$show = array_map(function($object){ 
return (array) $object; 
}, $breadcrumbs); 
@endphp 

@foreach ($show as $breadcrumb)
@php
	$url = $breadcrumb['url'];
	$title = $breadcrumb['title'];
@endphp


 @if (!$breadcrumb['last'])
<a style="text-decoration: underline; color: blue;" href="{{ $url }}">{{ $title }}</a>
&nbsp;&nbsp;&gt;&nbsp;&nbsp; 
@else {{ $breadcrumb['title'] }} 
@endif
@endforeach 
@endif

