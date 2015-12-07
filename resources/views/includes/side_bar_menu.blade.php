<div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li class="activities"><a href="{{route('activity.index') }}">Activities</a></li>
                <li class="organization"><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization</a></li>
                <li class="published-files"><a href="{{ route('list-published-files') }}">Published Files</a></li>
                <li class="users"><a href="{{route('admin.list-users')}}">Users</a></li>
                <li class="documents"><a href="#">Documents</a></li>
                <li class="downloads"><a href="#">Downloads</a></li>
                @if(Auth::user()->role_id == 1)
                    <li class="settings"><a href="{{ url('/settings') }}">Settings</a></li>
                @endif
                @if(Auth::user()->role_id == 3)
                    <li class="activity-logs"><a href="{{ route('admin.activity-log') }}">Activity Logs</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>