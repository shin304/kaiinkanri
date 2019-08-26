<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>{{$an::get('invoice_print_title')}}</title>
		<link rel="stylesheet" type="text/css" href="{{$_app_path}}css/invoice.css" />
	</head>
	<body>
		<div class="description">
			{{$error_occur_title}}
			<ul>
				@foreach($error as $item)
					<li>{{$item}}</li>
				@endforeach
			</ul>
		</div>
	</body>
</html>
