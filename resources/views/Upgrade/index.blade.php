@extends('app')

@section('title', 'Upgrade to Version ' . session('next_version'))

@section('content')
    <div class="container main-container">
		@if(session('next_version'))
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

							<p>This will upgrade all data to <a href="http://iatistandard.org/{{str_replace('.','',session('next_version'))}}/">IATI Version {{ session('next_version') }}</a> for <b>{{ $orgName }}</b>.</p>
							<p>Click Continue to upgrade your AidStream account to Version <a href="http://reference.iatistandard.org/{{str_replace('.','',session('next_version'))}}/">{{ session('next_version')}}</a>.<br> 
								Please note that your data won't be affected as the new standard changes are additions to the standards.<br> 
								You will see some changes in the forms and template. <br>
								@if(session('next_version') == '2.03')
								Please refer to this <a href="https://github.com/younginnovations/aidstream/wiki/V2.03-Changes-in-AidStream">Document</a> to see the changes in AidStream.</p>
								@endif
							<p>
								<a href="{{ route('upgrade-version.update', session('next_version')) }}" class="btn btn-primary">Continue</a> <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
@endsection
