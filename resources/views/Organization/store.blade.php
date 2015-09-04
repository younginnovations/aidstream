@extends('app')

@section('content')
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
			<div class="panel panel-default">
				<div class="panel-heading">Identification</div>
				<div class="panel-body">
					<ul class="nav">
						<li><a href="{{ url('/organization/reporting-organization') }}">Reporting Organization</a></li>
						<li><a href="{{ url('/organization/organization-identifier') }}">Organization Identifier</a></li>
						<li><a href="{{ url('/organization/organization-name') }}">Name</a></li>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Budgets</div>
				<div class="panel-body">
					<ul class="nav">
						<li><a href="{{ url('/organization/organization-data') }}">Total Budget</a></li>
						<li><a href="{{ url('/organization/organization-data') }}">Recipient Organization Budget</a></li>
						<li><a href="{{ url('/organization/organization-data') }}">Recipient Country Budget</a></li>
					</ul>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Documents</div>
				<div class="panel-body">
					<ul class="nav">
						<li><a href="{{ url('/organization/organization-data') }}">Document Link</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
