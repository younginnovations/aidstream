<nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
        <div class="navbar-brand">
            <div class="pull-left logo">
                <a href="{{url('/')}}" title="AidStream Nepal">
                    <img src="{{url('images/np/ic_aidstream-logo-np.png')}}" alt="AidStream Nepal Logo">
                </a>
            </div>
        </div>
    </div>
    <div class="navbar-dropdown-block">
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <ul class="nav navbar-nav navbar-right navbar-admin-dropdown">
                @if (auth()->guest())
                <li><a href="{{ url('/auth/login') }}">@lang('trans.login')</a></li>
                <li><a href="{{ url('/auth/register') }}">@lang('trans.register')</a></li>
                @endif
                <li class="dropdown" data-step="1" id="admin-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="avatar-img">
                            @if($loggedInUser->profile_url)
                                <img src="{{url($loggedInUser->profile_url)}}"
                                        width="36" height="36"
                                        alt="{{$loggedInUser->name}}">
                            @else
                                <img src="{{url('images/avatar.svg')}}"
                                        width="36" height="36"
                                        alt="{{$loggedInUser->name}}">
                            @endif
                        </span>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('/auth/logout') }}" id="logout">@lang('trans.logout')</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>