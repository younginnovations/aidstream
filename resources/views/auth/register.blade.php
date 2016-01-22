<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream - Register</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/flag-icon.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

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
    <script type="text/javascript" src="{{url('/js/main.js')}}"></script>

    @yield('head')

</head>
<body>
<div class="login-wrapper">
    <div class="language-select-wrapper">
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
    </div>
    <div class="container-fluid register-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="{{url('images/logo.png')}}" alt="">

                        <div class="panel-title">Register</div>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization Name</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="organization_name" value="{{ old('organization_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization Address</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="organization_address" value="{{ old('organization_address') }}">
                                        </div>
                                    </div>

                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Organization User Identifier</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="organization_user_identifier" value="{{ old('organization_user_identifier') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">First Name</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Last Name</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">E-Mail Address</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Country</label>

                                        <div class="col-xs-12 col-md-12">
                                            {{Form::select('country', ['' => 'Select Country'] + $countries)}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-wrapper no-border">
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Username</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="text" class="form-control" name="username" value="{{ old('username') }}" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label class="control-label">Password</label>

                                        <div class="col-xs-12 col-md-12">
                                            <input type="password" class="form-control" name="password">
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
            <div class="col-xs-12 col-md-12 logo-text">Aidstream</div>
            <div class="col-xs-12 col-md-12 support-desc">
                For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
