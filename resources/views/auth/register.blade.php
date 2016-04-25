<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream - Register</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form select').select2();
        });
    </script>

    @yield('head')
</head>
<body>
<header>
    <nav class="navbar navbar-default navbar-static">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand">Aidstream</a>
            <button type="button" class="navbar-toggle collapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a class="{{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">Who's Using It?</a></li>
                <li><a href="https://github.com/younginnovations/aidstream-new/wiki/User-Guide" target="_blank">User Guide</a></li>
                <!--<li><a href="#">Snapshot</a></li>-->
            </ul>
            <div class="action-btn pull-left">
                @if(auth()->check())
                    <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}" class="btn btn-primary">Go
                        to Dashboard</a>
                @else
                    <a href="{{ url('/auth/login')}}" class="btn btn-primary">Login/Register</a>
                @endif
            </div>
        </div>
    </nav>
</header>

<div class="login-wrapper">
    {{--    <div class="language-select-wrapper">
            <label for="" class="pull-left">Language</label>

            <div class="language-selector pull-left">
                <span class="flag-wrapper"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ config('app.locale') }}"></span></span>
                <span class="caret pull-right"></span>
            </div>
            <ul class="language-select-wrap language-flag-wrap">
                @foreach(config('app.locales') as $key => $val)
                    <li class="flag-wrapper" data-lang="{{ $key }}"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}"></span><span class="language">{{ $val }}</span></li>
                @endforeach
            </ul>
        </div>--}}
    <div class="container-fluid register-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="{{url('images/logo.svg')}}" alt="">

                        <div class="panel-title">Register</div>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>
                                  There were some problems with your input.
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                                </span>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization Name*</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="organization_name" value="{{ old('organization_name') }}" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization Address*</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="organization_address" value="{{ old('organization_address') }}" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization User Identifier*</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control noSpace" name="organization_user_identifier" value="{{ old('organization_user_identifier') }}" required="required">
                                            <span class="availability-check hidden"></span>
                                            <span class="help-block">Your organisation user identifier will be used as a prefix for all the AidStream users in your organisation. We recommend that you use a short abbreviation that uniquely identifies your organisation. If your organisation is 'Acme Bellus Foundation', your organisation user identifier should be 'abf', depending upon it's availability.</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 username_text">
                                        <label class="control-label">Username</label>
                                        <em>This will be auto-generated as you fill Organization User Identifier.</em>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6 username_value hidden">
                                        <label class="control-label">Username</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="hidden" class="form-control hover_help_text" name="username" value="{{ old('username') }}" readonly="readonly">

                                            <div class="alternate_input">{{ old('username') }}</div>
                                        <span class="help-text"
                                              title="AidStream will create a default username with your Organisation User Identifier as prefix. You will not be able to change '_admin' part of the username. This user will have administrative privilege and can create multiple AidStream users with different set of permissions."
                                              data-toggle="tooltip" data-placement="top">
                                           AidStream will create a default username with your Organisation User Identifier as prefix. You will not be able to change '_admin' part of the username. This user will have administrative privilege and can create multiple AidStream users with different set of permissions.
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Password*</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="password" class="form-control" name="password" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Confirm Password</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-wrapper no-border">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                            <label class="control-label">First Name*</label>

                                            <div class="col-xs-12 col-md-12">
                                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                            <label class="control-label">Last Name*</label>

                                            <div class="col-xs-12 col-md-12">
                                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                            <label class="control-label">E-Mail Address*</label>

                                            <div class="col-xs-12 col-md-12">
                                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                            <label class="control-label">Country*</label>

                                            <div class="col-xs-12 col-md-12">
                                                {{Form::select('country', ['' => 'Select Country'] + $countries, null, ['required' => true])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-submit btn-register">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 create-account-wrapper">
                <a href="{{ url('/auth/login') }}">I already have an account</a>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')

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

        $('[name="organization_user_identifier"]').on('change', checkAvailability);
        $('[name="organization_user_identifier"]').on('keydown', function () {
            $('.availability-check').html('').addClass('hidden').removeClass('text-success text-danger');
        });
        function checkAvailability() {
            var userIdentifier = $(this).val();
            if ($.trim(userIdentifier) == "") {
                return false;
            }
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                type: 'POST',
                url: '{{ route('check-organization-user-identifier') }}',
                data: {userIdentifier: $(this).val()},
                success: function (data) {
                    $('.availability-check').removeClass('hidden').addClass('text-' + data.status).html(data.message);
                }
            });
        }
    });
</script>
</body>
</html>
