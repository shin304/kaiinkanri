		@if (session()->has('appmanage.message'))
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<ul>
				<li>{{ session()->pull('appmanage.message') }}</li>
			</ul>
		</div>
		@endif

		@if (session()->has('appmanage.messages'))
		<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<ul>
				@foreach (session()->pull('appmanage.messages') as $messages) @foreach ($messages as $msg) <li> {{ $msg }} </li> @endforeach @endforeach
			</ul>
		</div>
		@endif