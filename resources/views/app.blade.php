<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream</title>

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
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ Auth::user()->role_id == 3 ? url('admin/dashboard') : url('/')  }}" style="text-indent: 145px; line-height: 35px;"
               alt="Aidstream">{{Auth::user() ? "(" . Session::get('version') . ")" : "Aidstream"}}</a>
        </div>

        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav pull-left add-new-activity">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false">Add a New Activity<span
                                class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{route('activity.create') }}">Add Activity Manually</a></li>
                        <li><a href="{{route('wizard.activity.create') }}">Add Activity using Wizard</a></li>
                        <li><a href="{{ route('activity-upload.index') }}">Upload Activities</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right navbar-admin-dropdown">
                @if (Auth::guest())
                    <li><a href="{{ url('/auth/login') }}">@lang('trans.login')</a></li>
                    <li><a href="{{ url('/auth/register') }}">@lang('trans.register')</a></li>
                @else
                    <li>
                        @if((Session::get('role_id') == 3  || Session::get('role_id') == 4) && Session::get('org_id'))
                            <span><a href="{{ route('admin.switch-back') }}" class="pull-left">Switch Back</a> You are masquerading as </span>
                        @endif
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><span class="avatar-img"><img src="{{url('images/avatar.png')}}" width="16" height="26" alt="{{Auth::user()->name}}"></span>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">My Profile</a></li>
                            <li><a href="{{ url('/auth/logout') }}">@lang('trans.logout')</a></li>
                            <li class="language-select-wrap">
                                <label for="">Language</label>
                                @foreach(config('app.locales') as $key => $val)
                                    <span class="flag-wrapper" data-lang="{{ $key }}">
                                        <span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}{{ $key == config('app.locale') ? ' active' : '' }}"></span>
                                    </span>
                                @endforeach
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
{{--*/
        $response = session('response');
    /*--}}
@if($response)
    <div class="alert alert-{{$response['type']}}">
        {{ message($response) }}
    </div>
@endif
@yield('content')

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{url('/js/main.js')}}"></script>

@yield('foot')

</body>
</html>
