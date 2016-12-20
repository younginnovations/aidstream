<footer>
    <div class="width-900">
        <div class="social-wrapper bottom-line">
            <div class="col-md-12 text-center">
                <ul>
                    <li><a href="https://github.com/younginnovations/aidstream-new" class="github" title="Fork us on Github">@lang('global.fork_us_on_github')</a></li>
                    <li><a href="https://twitter.com/aidstream" class="twitter" title="Follow us on Twitter">@lang('global.follow_us_on_twitter')</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-nav bottom-line">
            <div class="col-md-12">
                <ul>
                    <li><a href="{{ url('/about') }}">@lang('global.about')</a></li>
                    <li><a href="{{ url('/who-is-using') }}">@lang('global.who_is_using')</a></li>
                    <li><a href="https://github.com/younginnovations/aidstream-new/wiki/User-Guide" target="_blank">@lang('global.user_guide')</a></li>
                    <!--<li><a href="#">Snapshot</a></li>-->
                </ul>
                <ul>
                    @if(auth()->check())
                        <li>
                            <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}">@lang('global.go_to_dashboard')</a>
                        </li>
                    @else
                        <li><a href="{{ url('/auth/login') }}">@lang('global.login')</a></li>
                        <li><a href="{{ route('registration') }}">@lang('global.register')</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="footer-logo">
            <div class="col-md-12 text-center">
                <a href="{{ url('/') }}"><img src="/images/logo-aidstream.svg" alt=""></a>
            </div>
        </div>
    </div>
    <div class="width-900 text-center">
        <div class="col-md-12 support-desc">
            @lang('global.for_queries') <a href="mailto:support@aidstream.org">support@aidstream.org</a>
        </div>
    </div>
    <!-- Google Analytics -->
    <script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
    <!-- End Google Analytics -->
</footer>
