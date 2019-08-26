<!DOCTYPE html>
<html>
<head>
<title>Business Type</title> @include('_parts.html_header')
<link type="text/css" rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet"
	href="/css/BusinessSystem/business_system.css">
<script type="text/javascript" src="/js/school/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/system/business_system.js"></script>
</head>
<body>
	<div class="row header"></div>
	<div class="container">
		<div class="row" id="menu">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<pre class="total_count">@if($total >0) 全{{$total}}件.
				@else 表示する情報がありません。
				@endif
				</pre>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form action="/system/delete" method="post" id="content_delete">
					{{ csrf_field() }}
					<table class="sheet">
						<thread>
						<tr>
							<th></th>
							<th>ID</th>
							<th>業態名</th>
							<th>リソースファイル</th>
							<th>更新日</th>
							<th></th>
						</tr>
						</thread>
						@if($types)
						<tbody>
							@foreach($types as $type)
							<tr>
								<td><input type="checkbox" name="chk[{{$type->id}}]"></td>
								<td>{{$type->id}}</td>
								<td>{{$type->type_name}}</td>
								<td>{{$type->resource_file}}</td>
								<td>{{$type->update_date}}</td>
								<td><input type="button" value="編集"
									onclick="location.href='/system/edit?id={{$type->id}}'"></td>
							</tr>
							@endforeach
						</tbody>
						@endif
					</table>
					<div>{{$types->links()}}</div>
					<input type="button" onclick="location.href='/system/addNew'"
						value="登録"> &nbsp;&nbsp;&nbsp;<input type="button" id="btnDelete"
						value="削除">
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</body>
</html>