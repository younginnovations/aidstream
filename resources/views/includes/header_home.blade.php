<header>
    <nav class="navbar navbar-default navbar-static navbar-fixed">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand">@lang('title.aidstream')</a>
            <button type="button" class="navbar-toggle collapsed">
                <span class="sr-only">@lang('global.toggle_navigation')</span>
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a class="{{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">@lang('global.about')</a></li>
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">
                        @lang('global.who_is_using')
                    </a></li>
                <li><a href="https://github.com/younginnovations/aidstream-new/wiki/User-Guide" target="_blank">
                        @lang('global.user_guide')
                    </a></li>
                <!--<li><a href="#">Snapshot</a></li>-->
            </ul>
            <div class="action-btn pull-left">
                @if(auth()->check())
                    <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}"
                       class="btn btn-primary">
                        @lang('global.go_to_dashboard')
                    </a>
                @else
                    <a href="{{ url('/auth/login')}}" class="btn btn-primary">@lang('global.login')/@lang('global.register')</a>
                @endif
            </div>
        </div>
    </nav>
</header>