<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{ route('admin.list-organization') }}">List Organizations</a></li>
                @if(Auth::user()->role_id == 3)
                <li><a href="{{ route('admin.add-organization') }}">Add Organization</a></li>
                <li><a href="{{ route('admin.group-organizations') }}">Group Organization</a></li>
                <li><a href="">List Help Topics</a></li>
                <li><a href="#">Activity Status</a></li>
                <li><a href="#">Validate XMl</a></li>
                <li><a href="#">Generate Published XML Files</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
