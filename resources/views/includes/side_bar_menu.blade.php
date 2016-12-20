<div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li class="activities" data-step="2"><a href="{{ route('activity.index') }}">@lang('global.activities')</a>
                </li>
                <li class="organization" data-step="3"><a href="{{ route('organization.show', session('org_id')) }}">@lang('global.organisation')</a></li>
                <li class="published-files" data-step="4"><a href="{{ route('list-published-files') }}">@lang('global.published_files')</a></li>
                <li class="documents" data-step="5"><a href="{{ route('documents') }}">@lang('global.documents')</a></li>
                <li class="downloads" data-step="6"><a href="{{route('download.index')}}">@lang('global.downloads')</a></li>
                <li class="settings" data-step="7"><a href="{{ route('settings') }}">@lang('global.settings')</a></li>
                @if(Auth::user()->role_id == 1)
                    <li class="logs" data-step="8"><a href="{{ route('user-logs') }}">@lang('global.activity_log')</a></li>
                @endif
            </ul>
            <div class="support">
                <span>icon</span>

                <p>@lang('global.for_queries')<a href="mailto:support@aidstream.org"> support@aidstream.org</a></p>
            </div>
        </div>
    </div>
</div>
