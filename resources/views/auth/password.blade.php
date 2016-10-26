<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream - Forgot Password</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>
<body>
<header>
    <nav class="navbar navbar-default navbar-static navbar-fixed">
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
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">Who's Using</a></li>
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
    {{--		    <div class="language-select-wrapper">
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
    <div class="container-fluid login-container reset-container">
        <div class="row">
            <h1 class="text-center">Reset Password</h1>
            <p class="text-center">Please enter your email address below to reset your password.</p>
            <div class="col-lg-4 col-md-8 reset-block">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="input-wrapper reset-input-wrapper">
                                <div class="form-group">
                                    <label class="control-label required">Your E-Mail Address</label>

                                    <div class="col-md-12">
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    </div>
                                </div>
                                </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-submit">
                                    Send Password Reset Link
                                </button>
                            </div>
                            <div class="organisation-account-wrapper">
                                <p class="text-center">
                                    If you have forgotten which email address you used to Register with AidStream, please select your account type to continue.
                                </p>
                                <p>
                                    @if(session('same_identifier_org_id'))
                                        <a href="{{ route('submit-similar-organization', 'user') }}" class="btn btn-primary btn-submit">User Account</a>
                                        <a href="{{ route('submit-similar-organization', 'admin') }}" class="btn btn-primary btn-submit">Administrator Account</a>
                                    @else
                                        <a class="btn btn-primary btn-submit btn-type" data-type="user">User Account</a>
                                        <a class="btn btn-primary btn-submit btn-type" data-type="admin">Administrator Account</a>
                                    @endif
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Similar Organisations Modal -->
<div class="modal fade preventClose" id="similar-org-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg register-container" role="document">
        <div class="modal-content form-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body same-identifier org-warning">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                                <span>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                        </div>
                    @endif
                    <h1 class="text-center">Find your organization</h1>
                    <p class="text-center">
                        To help us recover your account details,please enter the name of your organisation in the field below.
                    </p>
                    <div class="similar-org-container">
                        {{ Form::open(['url' => route('submit-similar-organization'), 'method' => 'post', 'id' => 'similar-org-form']) }}

                        <div class="input-wrapper">
                            <div class="col-xs-12 col-md-12">
                                {{ Form::hidden('type') }}
                                {!! AsForm::text(['name' => 'search_org', 'class' => 'search_org ignore_change', 'label' => false]) !!}
                                {{ Form::button('Search Organisation', ['class' => 'btn btn-primary btn-search', 'type' => 'button']) }}
                                {{ Form::hidden('similar_organization') }}
                            </div>
                            <div class="org-list-container clickable-org hidden">
                                <div class="col-xs-12 col-md-12 organization-list-wrapper">
                                    <p class="text-center">Our database contains the following organisation/s which match the name of the organisation you entered. If one of them is your organisation,
                                        please click to select it.</p>
                                    <ul class="organization-list">
                                    </ul>
                                </div>
                                <div class="col-md-12 text-center org-list-notification">
                                    <p>None of the results above match my organisation. I would like to <a href="{{ url('/register') }}">register</a> my organisation for an Aidstream account.</p>
                                </div>
                            </div>
                            <div class="org-list-container no-org-list hidden">
                                <div class="col-xs-12 col-md-12 organization-list-wrapper">
                                    <p class="text-center">
                                        Our database doesn't contain the name of the organisation you entered.
                                        <br/>
                                        Would you like to <a href="{{ url('/register') }}">register</a> your organisation for an AidStream account?
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center clickable-org org-list-notification">
                            {{ Form::button('Continue', ['class' => 'btn btn-primary btn-submit btn-register prevent-disable hidden', 'type' => 'submit', 'disabled' => 'disabled']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
<!-- Scripts -->
@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/registration.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        Registration.filterSimilarOrg();
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }
        hamburgerMenu();
    });
</script>
</body>
</html>
