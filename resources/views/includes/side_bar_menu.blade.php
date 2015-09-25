<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{route('activity.index') }}">List Activities</a></li>
                <li><a href="{{route('activity.create') }}">Add New Activity</a></li>
                <li><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization Data</a></li>
                <li><a href="{{ route('list-published-files') }}">List Published Files</a></li>
                <li><a href="#">Download My Data</a></li>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{route('admin.list-users')}}">List Users</a></li>
                <li><a href="#">Uploaded Docs</a></li>
            </ul>
        </div>
    </div>
</div>