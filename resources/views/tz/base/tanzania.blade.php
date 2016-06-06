<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>AidStream - @yield('title', 'No Title')</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .navbar-brand > a,.footer-logo a{
            width: 130px;
            position: relative;
            display: block;
        }


        .tanzania-flag{
            position: absolute;
            width: 18px;
            height: 12px;
            background: url("{{ asset('/images/ic_tanzania-flag.svg') }}") center center  no-repeat;
            top: 13px;
            right: 0;
        }
    </style>
    @yield('head')

</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="navbar-brand">
                <a href="/" alt="Aidstream">Aidstream<span class="tanzania-flag"></span></a>
            </div>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            @if(auth()->user() && !isSuperAdminRoute())
                <ul class="nav navbar-nav pull-left add-new-activity">
                    <li class="dropdown">
                        <a href="{{ route('project.create') }}">Add Project</a>
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Add a New Project<span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li><a href="{{ route('project.create') }}">Add Project Manually</a></li>--}}
{{--                            <li><a href="{{ route('project.upload') }}">Upload Projects</a></li>--}}
                        {{--</ul>--}}
                    </li>
                </ul>
            @endif
            <ul class="nav navbar-nav navbar-right navbar-admin-dropdown">
                @if (Auth::guest())
                    <li><a href="{{ route('auth.login') }}">@lang('trans.login')</a></li>
                    <li><a href="{{ route('auth.register') }}">@lang('trans.register')</a></li>
                @else
                    <li>
                        @if((session('role_id') == 3  || session('role_id') == 4) && !isSuperAdminRoute())
                            <span><a href="{{ route('admin.switch-back') }}" class="pull-left">Switch Back</a></span>
                        @endif
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><span class="avatar-img"><img src="{{url('images/avatar.svg')}}" width="36" height="36" alt="{{Auth::user()->name}}"></span>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(!isSuperAdminRoute())
                                <li><a href="{{url('user/profile')}}">@lang('trans.my_profile')</a></li>
                            @endif
                            <li><a href="{{ url('/auth/logout') }}">@lang('trans.logout')</a></li>
                            <li class="pull-left width-491">
                                @if(!isSuperAdminRoute())
                                    <span class="width-490"><a href="{{ route('admin.switch-back') }}"
                                                               class="pull-left">Switch Back</a></span>
                                @endif
                            </li>
                            <li class="pull-left width-491">
                                <div class="navbar-left version-wrap width-490">
                                    @if(!isSuperAdminRoute())
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
        {{-- Unrequired --}}
        {{--<div class="navbar-right version-wrap">--}}
            {{--@if(auth()->user() && !isSuperAdminRoute())--}}
                {{--<div class="version pull-right {{ (session('version') == 'V201') ? 'old' : 'new' }}">--}}
                    {{--@if (session('next_version'))--}}
                        {{--<a class="version-text" href="{{route('upgrade-version.index')}}">Update available</a>--}}
                        {{--<span class="old-version">--}}
                            {{--<a href="{{route('upgrade-version.index')}}">Upgrade to IATI--}}
                                {{--version {{ session('next_version') }} </a>--}}
                      {{--</span>--}}
                    {{--@else--}}
                        {{--<span class="version-text">IATI version {{ session('current_version') }}</span>--}}
                        {{--<span class="new-version">--}}
                   {{--You’re using latest IATI version--}}
                 {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--@endif--}}
        {{--</div>--}}
    </div>
</nav>

<div class="container main-container">
    <div class="row">
        @yield('sub-content')
        @yield('content')
    <div>
</div>


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
<script type="text/javascript">
    $(document).ready(function () {
        $('form select').select2();
    });
</script>
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<!-- End Google Analytics -->
@yield('script')
@yield('foot')

</body>
</html>
