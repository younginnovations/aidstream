@extends('tz.base.tanzania')

@section('sub-content')
    <div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav">
                    <li class="activities"><a href="{{ route('project.index') }}">Projects</a></li>
                    <li class="published-files"><a href="{{ route('published-files.list') }}">Published Files</a></li>
                    <li class="users"><a href="{{ route('users.list') }}">Users</a></li>
                    <li class="downloads"><a href="{{ route('downloads') }}">Downloads</a></li>
                    @if(auth()->user()->role_id == 1)
                        <li class="settings"><a href="{{ route('settings.index') }}">Settings</a></li>
                        {{--<li class="logs"><a href="{{ route('user-logs') }}">Activity Log</a></li>--}}
                    @endif
                </ul>
                <div class="support">
                    <span>icon</span>
                    <p>For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
                </div>
            </div>
        </div>
    </div>
@stop
