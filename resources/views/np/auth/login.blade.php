<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
	<title>@lang('title.aidstream_login')</title>

	<link rel="shortcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
	<link href="{{ asset('/css/vendor.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">

	{!!  publicStylesheet() !!}

	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="municipality-login">
{{--Header--}}
<section class="header-banner">
	@include('np.partials.header')
</section>
{{--Login--}}
<div class="login-wrapper">
	<div class="container-fluid login-container">
		<div class="row">
			<h1 class="text-center">@lang('global.login')</h1>
			<div class="col-lg-4 col-md-8 login-block">
				<div class="panel panel-default">
					<div class="panel-body">
						@if (count($errors) > 0)
							<div class="alert alert-danger">
                                <span>
                                  <ul>
                                      @foreach ($errors->all() as $error)
										  <li>{!! $error !!}</li>
									  @endforeach
                                  </ul>
                                </span>
							</div>
						@endif

						@if(Session::get('message'))
							<div class="alert alert-success">
								<span>{{ Session::get('message') }}</span>
							</div>
						@endif

						<form role="form" method="POST" action="{{ url('/auth/login') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="login-form-group">
								<div class="form-group">
									<label class="control-label">@lang('trans.login_name')</label>
									<div class="col-md-12">
										<input type="text" class="form-control ignore_change" name="login"
											   value="{{ old('login') }}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">@lang('trans.password')</label>

									<div class="col-md-12">
										<input type="password" class="form-control ignore_change" name="password">
									</div>
								</div>
								<div class="form-group">
									{{--<div class="col-md-6 pull-left">--}}
									{{--<div class="checkbox">--}}
									{{--<label>--}}
									{{--<input type="checkbox" name="remember"--}}
									{{--class="ignore_change"> @lang('trans.remember_me')--}}
									{{--</label>--}}
									{{--</div>--}}
									{{--</div>--}}
									<div class="col-md-6 pull-right">
										<a class="btn-link"
										   href="{{ url('/password/email') }}">@lang('trans.forgot_password')?</a>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary btn-submit">@lang('trans.login')</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-12 create-account-wrapper">
				@lang('global.dont_have_account') <a href="{{ route('registration') }}">@lang('global.create_account')</a>
			</div>
		</div>
	</div>

</div>
{{--Footer--}}
@include('np.partials.footer')
<!-- Scripts -->
@if(env('APP_ENV') == 'local')
	<script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
	<script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script>
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }

        hamburgerMenu();
    });
</script>


@if(session('verification_message'))
	<div class="modal fade verification-modal" tabindex="-1" role="dialog" style="display: block;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<img src="{{ url('/images/ic-verified.svg') }}" alt="verified" width="66" height="66">
					<h4 class="modal-title text-center">@lang('global.verification_successful')</h4>
				</div>
				<div class="modal-body clearfix">
					{!! session('verification_message') !!}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
        $(document).ready(function () {
            $('.verification-modal').modal('show');
        });

        $('#add-this-later').on('click', function () {
            $('.verification-modal').modal('hide');

            var code = $(this).attr('data-code');

            $.ajax({
                type: 'POST',
                url: '/add-publishing-info-later',
                data: {'code': code},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).success(function (response) {
                if (response.success) {
                    location.reload();
                }
            });
        });
	</script>
@endif
</body>
</html>
