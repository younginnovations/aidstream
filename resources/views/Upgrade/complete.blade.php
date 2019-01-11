@extends('app')

@section('title', 'Upgrade Complete')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
				@include('includes.response')
				<div class="panel panel-default">
					<div class="panel-content-heading panel-title-heading">
						Upgrade Complete
					</div>
					<div class="panel-body">
						<div class="upload-wrapper">
                            <p>Congratulations, you have successfully upgraded your AidStream account to {{substr_replace(session('version'), '.', 2, 0)}} </p>
                            
                            <p>Please note that your data is not effected as the new standard changes are additions to the standards. You will see some changes in the forms and template as per {{substr_replace(session('version'), '.', 2, 0)}}.</p>

                            <p><strong>What you need to do now</strong></p>
                            <ul style="list-style-type:disc">
                            <li>Republish at least one of your activities to publish your XML files with {{substr_replace(session('version'), '.', 2, 0)}}</li>
                            @if(session('version') == 'V203')
                            <li>Refer to this <a href="https://github.com/younginnovations/aidstream/wiki/V2.03-Changes-in-AidStream">Document</a> to see what changes have been made in Aidstream</li>
                            @endif
                            <li>Email one of our support people at <a href="mailto:support@aidstream.org">support@aidstream.org</a> if you come across any issue</li>
                            </ul>
                        </p>
                        <a href="../activity" class="btn btn-primary">Continue</a> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
