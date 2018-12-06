<header>
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand" title="AidStream Nepal"><img src="/images/np/ic_aidstream-logo-np.png" alt="AidStream Nepal" width="140px" height="40px"></span></a>
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
                <li><a href="https://github.com/younginnovations/aidstream/wiki/User-Guide" target="_blank">User Guide</a></li>
            </ul>
            <div class="action-btn pull-left">
                @if(auth()->check())
                    @if(isMunicipalityAdmin())
                    <a href="{{ config('app.municipality_dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                    <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? route('np.activity.index') : config('app.super_admin_dashboard'))}}" class="btn btn-primary">Go to Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login.overridden') }}" class="btn btn-primary">Login/Register</a>
                @endif
            </div>
        </div>
    </nav>
</header>
