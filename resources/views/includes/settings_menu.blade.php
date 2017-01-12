<div class="settings-tab-wrapper">
    <div class="element-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav">
                    <li @if(request()->path() == 'settings') class="active" @endif>
                        <a href="{{route('settings')}}">
                            @lang('setting.organisation_information')
                        </a>
                    </li>
                    <li @if(request()->path() == 'publishing-settings') class="active"@endif >
                        <a href="{{route('publishing-settings')}}">@lang('setting.publishing_settings')</a>
                    </li>
                    <li @if(request()->path() == 'activity-elements-checklist') class="active" @endif>
                        <a href="{{route('activity-elements-checklist')}}">@lang('setting.activity_elements_checklist')</a>
                    </li>
                    <li @if(request()->path() == 'default-values') class="active" @endif>
                        <a href="{{route('default-values')}}">@lang('setting.default_values')</a>
                    </li>
                    <li @if(request()->path() == 'organization-user' || request()->path() == 'organization-user/register') class="active" @endif>
                        <a href="{{route('admin.list-users')}}">@lang('setting.users')</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
