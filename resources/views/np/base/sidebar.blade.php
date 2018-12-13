<div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li class="activities">
                    <a href="{{ route('np.activity.index') }}">@lang('lite/global.activities')</a>
                </li>
                <li class="published-files">
                    <a href="{{ route('np.published-files.index') }}">@lang('lite/global.published_files')</a>
                </li>
                <li class="users">
                    <a href="{{ route('np.users.index') }}">@lang('lite/global.users')</a>
                </li>
                <li class="settings" data-step="7">
                    <a href="{{ route('np.settings.edit') }}">@lang('lite/global.settings')</a>
                </li>
            </ul>
            <div class="support">
                <span>icon</span>
                <p>For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
            </div>
        </div>
    </div>
</div>
