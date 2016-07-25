<div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav">
                <li class="activities" id="step-1"><a href="{{ route('activity.index') }}">Activities</a></li>
                <li class="organization" id="step-3"><a href="{{ route('organization.show', session('org_id')) }}">Organisation</a></li>
                <li class="published-files" ><a href="{{ route('list-published-files') }}">Published Files</a></li>
                {{--<li class="users"><a href="{{route('admin.list-users')}}">Users</a></li>--}}
                <li class="documents" id="step-4"><a href="{{ route('documents') }}">Documents</a></li>
                <li class="downloads" id="step-5"><a href="{{route('download.index')}}">Downloads</a></li>
                @if(Auth::user()->role_id == 1)
                    <li class="settings" id="step-6"><a href="{{ route('settings') }}">Settings</a></li>
                @endif
                @if(Auth::user()->role_id == 1)
                    <li class="logs" id="step-7"><a href="{{ route('user-logs') }}">Activity Log</a></li>
                @endif
            </ul>
            <div class="support">
                <span>icon</span>

                <p>For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
            </div>
        </div>
    </div>
</div>
