@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Settings</div>
				<div class="panel-body">

					{!! form_start($form) !!}

						<div class="panel panel-default">
							<div class="panel-heading">Version</div>
							<div class="panel-body">
							{!! form_row($form->version_form) !!}
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">Reporting Organization Info</div>
							<div class="panel-body">
							{!! form_row($form->reporting_organization_info) !!}
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">Publishing Type</div>
							<div class="panel-body">
							{!! form_row($form->publishing_type) !!}
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">Registry Info</div>
							<div class="panel-body">
							{!! form_row($form->registry_info) !!}
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">Default Field Values</div>
							<div class="panel-body">
							{!! form_row($form->default_field_values) !!}
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">Default Field Groups</div>
							<div class="panel-body">
								<label><input type="checkbox" class="hidden checkAll"/><span class="btn btn-primary">Check All</span></label>
								{!! form_row($form->default_field_groups) !!}
							</div>
						</div>

					{!! form_end($form) !!}

					{{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="panel panel-default">
							<div class="panel-heading">Reporting Organisation Info</div>
							<div class="panel-body">

								<div class="form-group">
									<label class="col-md-4 control-label">Reporting Organisation Identifier:</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="login" value="{{ old('login') }}">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">Reporting Organisation Type:</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="login" value="{{ old('login') }}">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">Reporting Organisation Name:</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="login" value="{{ old('login') }}">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">Reporting Organisation Language:</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="login" value="{{ old('login') }}">
									</div>
								</div>

							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>--}}

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
