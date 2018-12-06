<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
	<title>AidStream - @yield('title', 'No Title')</title>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
	{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>--}}
	<link href="{{ asset('/css/vendor.min.css') }}" rel="stylesheet">
	{!! authStyleSheets() !!}

	<link href="{{ asset('/np/css/np.min.css') }}" rel="stylesheet">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.1.0/introjs.min.css" rel="stylesheet"/>

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	@yield('head')
</head>
<body class="municipality-admin">
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-header">
		<div class="navbar-brand">
			<div class="pull-left logo">
				<a href="{{url('/')}}" title="AidStream Nepal">
					<img src="{{url('images/np/ic_aidstream-logo-np.png')}}" alt="AidStream Nepal Logo">
				</a>
			</div>
		</div>
	</div>
	<div class="navbar-dropdown-block">
		<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
			<input type="hidden" name="_token" value="{{csrf_token()}}"/>
			@if(auth()->user() && !isSuperAdminRoute() && !isMunicipalityAdmin())
				<ul class="nav navbar-nav pull-left add-new-activity">
					<li class="dropdown"><a href="{{route('np.activity.create') }}">Add a New Activity</a></li>
					{{--<li class="dropdown" data-step="0">--}}
					{{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
					{{--aria-expanded="false">Add a New Activity<span--}}
					{{--class="caret"></span></a>--}}
					{{--<ul class="dropdown-menu" role="menu">--}}
					{{--<li><a href="{{route('lite.activity.create') }}">Add Activity</a></li>--}}
					{{--</ul>--}}
					{{--</li>--}}
				</ul>
			@endif
			<ul class="nav navbar-nav navbar-right navbar-admin-dropdown">
				@if (auth()->guest())
					<li><a href="{{ url('/auth/login') }}">@lang('trans.login')</a></li>
					<li><a href="{{ url('/auth/register') }}">@lang('trans.register')</a></li>
				@else
					<li>
						@if((session('role_id') == 3  || session('role_id') == 4 || session('role_id') == 8) && !isSuperAdminRoute())
							@if(isMunicipalityAdminRoute())
							@elseif(session('role_id') == 8)
								<span><a href="{{ route('municipalityAdmin.switch-back') }}" class="pull-left">Switch Back</a></span>
							@else
								<span><a href="{{ route('admin.switch-back') }}" class="pull-left">Switch Back</a></span>
							@endif
						@endif
					</li>
					<li class="dropdown" data-step="1" id="admin-dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
						   aria-expanded="false"><span class="avatar-img">
                                @if($loggedInUser->profile_url)
									<img src="{{url($loggedInUser->profile_url)}}"
										 width="36" height="36"
										 alt="{{$loggedInUser->name}}">
								@else
									<img src="{{url('images/avatar.svg')}}"
										 width="36" height="36"
										 alt="{{$loggedInUser->name}}">
								@endif
                            </span>
							<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							@if(!isSuperAdminRoute() && !isMunicipalityAdmin())
								<li><a href="{{url('/usr/profile')}}">@lang('trans.my_profile')</a></li>
							@endif
							<li><a href="{{ url('/auth/logout') }}" id="logout">@lang('trans.logout')</a></li>
							@if (superAdminIsLoggedIn() &&  !isMunicipalityAdmin())
								<li>
									<a href="{{ route('activity.index') }}">Core</a>
								</li>
							@endif
							@include('unwanted')

							<li class="pull-left width-491">
								@if(!isSuperAdminRoute())
									<span class="width-490"><a href="{{ route('admin.switch-back') }}" class="pull-left">Switch Back</a></span>
								@endif
							</li>
							<li class="pull-left width-491">
								<div class="navbar-left version-wrap width-490">
									@if(!isSuperAdminRoute() &&  !isMunicipalityAdmin())
										<div class="version pull-right {{ (session('version') == 'V201') ? 'old' : 'new' }}">
											@if ((session('version') == 'V201'))
												<a class="version-text" href="{{route('upgrade-version.index')}}">Update
													available</a>
												<span class="old-version">
                                                 <a href="{{route('upgrade-version.index')}}">Upgrade to IATI version
													 {{ session('next_version') }}</a>
                                              </span>
											@else
												<span class="version-text">IATI version {{session('current_version')}}</span>
												<span class="new-version">
                                               You’re using latest IATI version
                                             </span>
											@endif
										</div>
									@endif
								</div>
							</li>
						</ul>
					</li>
				@endif
			</ul>
		</div>
		@if(!isMunicipalityAdminRoute())
			<div class="downloads pull-right" data-step="6"><a href="{{route('np.csv.download')}}">@lang('lite/global.download_as_csv')</a></div>
		@endif

		@if($loggedInUser && !isSuperAdminRoute() &&  !isMunicipalityAdmin())
			<div class="navbar-right version-wrap">
				<div class="version pull-right {{ (session('version') == 'V201') ? 'old' : 'new' }}">
					@if (session('next_version'))
						<a class="version-text" href="{{route('upgrade-version.index')}}">Update available</a>
						<span class="old-version">
                            <a href="{{route('upgrade-version.index')}}">Upgrade to IATI
                                version {{ session('next_version') }} </a>
                      </span>
					@else
						<span class="version-text">IATI version {{ session('current_version') }}</span>
						<span class="new-version">
                   You’re using latest IATI version
                 </span>
					@endif
				</div>
			</div>
		@endif
	</div>
</nav>

<div class="container main-container">
	<div class="row">
		@yield('sub-content')
		@yield('content')
	</div>
</div>

@include('lite.partials.confirmDelete')

<div class="scroll-top">
	<a href="#" class="scrollup" title="Scroll to top">icon</a>
</div>

<!-- Scripts -->
<script type="text/javascript">
    var dateFields = document.querySelectorAll('form [type="date"]');
    for (var i = 0; i < dateFields.length; i++) {
        dateFields[i].setAttribute('type', 'text');
        dateFields[i].setAttribute('autocomplete', 'off');
        dateFields[i].classList.add('datepicker');
    }
</script>

@if(env('APP_ENV') == 'local')
	<script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/modernizr.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/select2.min.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery.datetimepicker.full.min.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/script.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/datatable.js')}}"></script>
@else
	<script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
@yield('humanitarian-script')

<script type="text/javascript">
    $('.delete-confirm').on('click', function () {
        var form = $('#delete-form');

        $('#modal-message').html($(this).attr('data-message'));

        form.attr('action', $(this).attr('data-href'));
        form.children('input#index').attr('value', $(this).attr('data-index'));
    });

    $(document).ready(function () {
        $('form select').select2();
    });
</script>
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.1.0/intro.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.js"></script>
<!--D3-->
<script src="https://d3js.org/d3.v4.min.js"></script>
@yield('script')

@yield('foot')

</body>
</html>
