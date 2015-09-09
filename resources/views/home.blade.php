@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-8">
			<div class="panel panel-default">
				<div class="panel-heading">@lang('trans.home')</div>

				<div class="panel-body">
					You are logged in!
				</div>
			</div>
		</div>

		<div class="col-xs-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav">
						<li><a href="#">List Activities</a></li>
						<li><a href="#">Add New Activity</a></li>
						<li><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization Data</a></li>
						<li><a href="#">List Published Files</a></li>
<<<<<<< HEAD
=======
						<li><a href="#">Download My Data</a></li>
>>>>>>> d57d9a5b03c2188fa6fe65b10f23358c770c342b
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<ul class="nav">
						<li><a href="#">List Users</a></li>
						<li><a href="#">Uploaded Docs</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
