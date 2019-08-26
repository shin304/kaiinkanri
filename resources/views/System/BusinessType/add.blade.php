<!DOCTYPE html>
<html>
<head>
<title>Business Type Add</title> @include('_parts.html_header')
<link type="text/css" rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet"
	href="/css/BusinessSystem/business_system.css">
</head>
<body>
	<div class="row header"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				@if(isset($type['id']))
				<h1>業態編集</h1>
				@else
				<h1>業態登録</h1>
				@endif
			</div>
		</div>
		@if(isset($type['error']))
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<p class="error_add">
					@if(isset($type['error']['type_name']))
					@if($type['error']['type_name']=='exist') 業態名は既に存在しています。<br />
					@else 業態名は必須です。<br /> @endif @endif
					@if(isset($type['error']['resource_file'])) リソースファイルは必須です。<br />@endif
				</p>
			</div>
			<div class="col-md-2"></div>
		</div>
		@endif
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form method="post" id="frmDoAdd" enctype="multipart/form-data"
					@if(isset($type['id'])) action="/system/edit?id={{$type['id']}}"
					@else action="/system/doAdd" @endif>
					@if(isset($type['id']))<input type="hidden" name='id'
						value="{{$type['id']}}"> @endif {{ csrf_field() }}
					<table class="formAdd">
						<tr>
							<th>業態名<span class="require"></span> :
							</th>
							<td><input type="text" name="type_name"
								@if(isset($type)) value="{{$type['type_name']}}" @endif></td>
						</tr>
						<tr>
							<th>リソースファイル<span class="require"></span> :
							</th>
							<td><input type="text" name="resource_file"
								@if(isset($type)) value="{{$type['resource_file']}}" @endif></td>
							<!-- <td><input type="file" name="resource_file" id="resource_file" accept = "text/*" /></td> -->
						</tr>
						<!-- @if(isset($type[0]->resource_file))
				<tr>
					<th>Current file : </th>
					<td><a name="cur_resource_file" href="/storage/app/BusinessType/{{$type[0]->resource_file}}">{{$type[0]->resource_file}}</a></td>
				</tr>
				@endif -->
						<tr>
							<td></td>
							<td><input type="submit" value="保存"></td>
						</tr>
					</table>

				</form>
			</div>
		</div>
	</div>
</body>
</html>