<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream - Login</title>

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

    @yield('head')

</head>
<body>
<div class="login-wrapper">
    {{--<div class="language-select-wrapper">--}}
        {{--<label for="" class="pull-left">Language</label>--}}
    {{--<div class="language-selector pull-left">--}}
        {{--<span class="flag-wrapper"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ config('app.locale') }}"></span></span>--}}
        {{--<span class="caret pull-right"></span>--}}
    {{--</div>--}}
            {{--<ul class="language-select-wrap language-flag-wrap">--}}
                {{--@foreach(config('app.locales') as $key => $val)--}}
                {{--<li class="flag-wrapper" data-lang="{{ $key }}"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}"></span><span class="language">{{ $val }}</span></li>--}}
                {{--@endforeach--}}
            {{--</ul>--}}
    {{--</div>--}}
    <div class="container-fluid login-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="{{url('images/logo.png')}}" alt="">
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>
                                  <strong>Whoops!</strong> There were some problems with your input.
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="control-label">@lang('trans.login_name')</label>

                                <div class="col-md-12">
                                    <input type="text" class="form-control ignore_change" name="login" value="{{ old('login') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">@lang('trans.password')</label>

                                <div class="col-md-12">
                                    <input type="password" class="form-control ignore_change" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 pull-left">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" class="ignore_change"> @lang('trans.remember_me')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 pull-right">
                                    <a class="btn-link" href="{{ url('/password/email') }}">@lang('trans.forgot_password')?</a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-submit">@lang('trans.login')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 create-account-wrapper">
                Don’t have an AidStream account? <a href="{{ url('/auth/register') }}">Create an account</a>
            </div>
            <div class="col-md-12 logo-text">Aidstream</div>
            <div class="col-md-12 support-desc">
                For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{url('/js/main.js')}}"></script>
</body>
</html>
