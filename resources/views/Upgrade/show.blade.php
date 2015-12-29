@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
				<div class="panel panel-default">
					<div class="panel-content-heading panel-title-heading">
						Upgrade
					</div>
					<div class="panel-body">
						<div class="create-form">
							This will upgrade all data to version {{ $version }} compatible data for organization {{ $orgId }}.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
