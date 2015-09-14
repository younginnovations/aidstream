@extends('app')

@section('content')

	{{Session::get('message')}}

<div class="container">
	<div class="row">
		<div class="col-xs-8">
			<div class="panel panel-default">
				<div class="panel-heading">Organization Data</div>

				<div class="panel-body">
					organization data content
				</div>
			</div>
		</div>

		<div class="col-xs-4">
			@include('includes.menu_org')
		</div>

	</div>
</div>
@endsection
