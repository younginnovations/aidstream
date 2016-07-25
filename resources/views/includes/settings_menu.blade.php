<div class="element-menu-wrapper">
    <div class="element-sidebar-dropdown">
        <div class="edit-element">edit<span class="caret"></span></div>
    </div>
    <div class="element-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav">
                    <li @if(request()->path() == 'settings') class="active" @endif>
                        <a href="{{route('settings')}}">
                            Organisation Information
                        </a>
                    </li>
                    <li @if(request()->path() == 'publishing-settings') class="active"@endif >
                        @if(!$settings)
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @elseif(is_null($settings->registry_info))
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @endif
                        <a href="{{route('publishing-settings')}}">Publishing Settings</a>
                    </li>
                    <li @if(request()->path() == 'activity-elements-checklist') class="active" @endif>
                        @if(!$settings)
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @elseif(is_null($settings->default_field_groups))
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @endif
                        <a href="{{route('activity-elements-checklist')}}">Activity Elements Checklist</a>
                    </li>
                    <li @if(request()->path() == 'default-values') class="active" @endif>
                        @if(!$settings)
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @elseif(is_null($settings->default_field_values))
                            <i class="glyphicon glyphicon-alert" style="color:orange"></i>
                        @endif
                        <a href="{{route('default-values')}}">Default Values
                        </a>
                    </li>
                    <li @if(request()->path() == 'admin.list-users') class="active" @endif>
                        <a href="{{route('admin.list-users')}}">Users
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
