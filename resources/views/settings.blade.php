@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
				<div class="panel panel-default">
					<div class="panel-content-heading">Settings</div>
					<div class="panel-body">
						<div class="create-form">
							{!! form($form) !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
