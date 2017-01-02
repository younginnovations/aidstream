@extends('lite.base.base')

@section('sub-content')
    <div class="col-xs-12 col-md-3 col-lg-3 sidebar-wrapper">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav">
                    <li class="activities"><a href="{{ route('lite.activity.index') }}">@lang('lite/global.activities')</a></li>
                    {{--<li class="organization" data-step="3"><a href="{{ route('organization.show', session('org_id')) }}">Organisation</a></li>--}}
                    <li class="published-files"><a href="{{ route('lite.published-files.index') }}">@lang('lite/global.published_files')</a></li>
                    <li class="users"><a href="{{route('lite.users.index')}}">@lang('lite/global.users')</a></li>
                    {{--<li class="documents" data-step="5"><a href="{{ route('documents') }}">Documents</a></li>--}}
                    <li class="downloads" data-step="6"><a href="{{route('lite.csv.download')}}">@lang('lite/global.download_as_csv')</a></li>
                    <li class="settings" data-step="7">
                        <a href="{{ $loggedInUser->organization->settings ? route('lite.settings.edit') : route('lite.settings.index') }}">@lang('lite/global.settings')</a></li>
                    {{--@if(Auth::user()->role_id == 1)--}}
                    {{--<li class="logs" data-step="8"><a href="{{ route('user-logs') }}">Activity Log</a></li>--}}
                    {{--@endif--}}
                </ul>
                <div class="support">
                    <span>icon</span>

                    <p>For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
                </div>
            </div>
        </div>
    </div>

@stop
