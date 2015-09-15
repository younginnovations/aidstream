@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-8">
			<div class="panel panel-default">
				<div class="panel-heading">Organization Data</div>
				<div class="panel-body">

					<ol class="breadcrumb">
						<?php $status_label = ['Draft', 'Completed', 'Verified', 'Published']; ?>
						@foreach($status_label as $key => $val)
							@if($key == $organization->status)
								<li class="active">{{ $val }}</li>
							@else
								<li><a href="#">{{ $val }}</a></li>
							@endif
						@endforeach
					</ol>

					<form method="POST" id="change_status">
						<input type="hidden" name="_method" value="PUT"/>
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<input type="hidden" name="status" value="2">
						<input type="submit" name="submit" value="Verify" class="btn_pop_dialog" data-title="Confirmation" data-message="Are you sure you want to change status?">
					</form>

					<div class="panel panel-default">
						<div class="panel-heading">Reporting Organization</div>
						<div class="panel-body row">
							<div class="col-xs-4">Ref:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
							<div class="col-xs-4">Type:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_type'] }}</div>
							@foreach($reporting_org['narrative'] as $narrative)
								<div class="col-xs-4">Narrative Text:</div>
								<div class="col-xs-8">{{ $narrative['narrative'] . ' [' . $narrative['language'] . ']' }}</div>
							@endforeach
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Organization Identifier</div>
						<div class="panel-body row">
							<div class="col-xs-4">Text:</div>
							<div class="col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Name</div>
						<div class="panel-body row">
							<div class="col-xs-4">Text:</div>
							<div class="col-xs-8">{{ $organization->name }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xs-4">
			@include('includes.menu_org')
		</div>

	</div>
</div>
@endsection
