<div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li class="activities"><a href="{{ route('activity.index') }}" class="{{ getLinkStatus(route('activity.index')) }}">Activities</a></li>
                <li class="organization"><a href="{{ route('organization.show', Session::get('org_id')) }}" class="{{ getLinkStatus(route('organization.show', Session::get('org_id'))) }}">Organization</a></li>
                <li class="published-files"><a href="{{ route('list-published-files') }}" class="{{ getLinkStatus(route('list-published-files')) }}">Published Files</a></li>
                <li class="users"><a href="{{route('admin.list-users')}}" class="{{ getLinkStatus(route('admin.list-users')) }}">Users</a></li>
                <li class="documents"><a href="{{ route('documents') }}" class="{{ getLinkStatus(route('documents')) }}">Documents</a></li>
                <li class="downloads"><a href="{{route('download.index')}}" class="{{ getLinkStatus(route('download.index')) }}">Downloads</a></li>
                @if(Auth::user()->role_id == 1)
                    <li class="settings"><a href="{{ route('settings.index') }}" class="{{ getLinkStatus(route('settings.index')) }}">Settings</a></li>
                @endif
                @if(Auth::user()->role_id == 3)
                    <li class="activity-logs"><a href="{{ route('admin.activity-log') }}" class="{{ getLinkStatus(route('admin.activity-log')) }}">Activity Logs</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
