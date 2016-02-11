@extends('app')

@section('title', 'Upgrade to Version ' . $version)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
				@include('includes.response')
				<div class="panel panel-default">
					<div class="panel-content-heading panel-title-heading">
						Upgrade
					</div>
					<div class="panel-body">
						<div class="upload-wrapper">
							<p>This will upgrade all data to version {{ $version }} compatible data for organization {{ $orgId }}.</p>
							<p>
								<a href="{{ route('upgrade-version.update', $version) }}" class="btn btn-primary">Continue</a> <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
