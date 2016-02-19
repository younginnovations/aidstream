<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AidStream - @yield('title', 'No Title')</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/flag-icon.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui-1.10.4.tooltip.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>


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
            <!--             <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button> -->

            <div class="navbar-brand">
                <a href="{{ Auth::user()->role_id == 3 ? url('admin/dashboard') : route('activity.index')  }}"
                   alt="Aidstream">Aidstream</a>
            </div>

            </div>


        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            @if(Auth::user()->role_id != 3 && Auth::user()->role_id !=4)
                <ul class="nav navbar-nav pull-left add-new-activity">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Add a New Activity<span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{route('activity.create') }}">Add Activity Manually</a></li>
                            {{--                            <li><a href="{{route('wizard.activity.create') }}">Add Activity using Wizard</a></li>--}}
                            <li><a href="{{ route('activity-upload.index') }}">Upload Activities</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
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
                           aria-expanded="false"><span class="avatar-img"><img src="{{url('images/avatar.png')}}" width="36" height="36" alt="{{Auth::user()->name}}"></span>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::user()->role_id != 3 && Auth::user()->role_id !=4)
                                <li><a href="{{url('user/profile')}}">@lang('trans.my_profile')</a></li>
                            @endif
                            <li><a href="{{ url('/auth/logout') }}">@lang('trans.logout')</a></li>
                            {{--<li class="language-select-wrap">--}}
                            {{--<label for="">Choose Language</label>--}}
                            {{--@foreach(config('app.locales') as $key => $val)--}}
                            {{--<span class="flag-wrapper" data-lang="{{ $key }}">--}}
                            {{--<span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}{{ $key == config('app.locale') ? ' active' : '' }}"></span>--}}
                            {{--</span>--}}
                            {{--@endforeach--}}
                            {{--</li>--}}
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <div class="navbar-right version-wrap">
            @if(Auth::user()->role_id != 3 && Auth::user()->role_id !=4)
                <div class="version pull-right {{ (Session::get('version') == 'V201') ? 'old' : 'new' }}">

                    @if ((Session::get('version') == 'V201'))
                        <a class="version-text" href="{{route('upgrade-version.index')}}">Update available</a>
                        <span class="old-version">
                         <a href="{{route('upgrade-version.index')}}">Upgrade to IATI version 2.0.2</a>
                      </span>
                    @else
                        <span class="version-text">IATI version V202</span>
                        <span class="new-version">
                   You’re using latest IATI version
                 </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</nav>

@yield('content')

        <!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{url('/js/main.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>
<script type="text/javascript">
    $('select').select2();
</script>
@yield('foot')

</body>
</html>
