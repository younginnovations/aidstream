@extends('np.mcpAdmin.includes.sidebar')

@section('title', trans('lite/title.activities'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading dashboard-panel__heading">
                <div>
                    <div class="panel__title">@lang('lite/activityDashboard.activities')</div>
                    <i>
                        {{-- @if($lastPublishedToIATI)
                            @lang('lite/activityDashboard.last_published_to_iati')
                            : {{substr(changeTimeZone($lastPublishedToIATI),0,12)}}
                        @endif --}}
                    </i>
                    <p>
                        @lang('lite/activityDashboard.find_activities_and_stats')
                    </p>
                </div>
            </div>
            <div class="panel__body">
                    <div class="text-center no-data no-activity-data">
                        <p>@lang('lite/global.not_added',['type' => trans('global.activity')]))</p>
                        <a href=""
                           class="btn btn-primary">@lang('lite/global.add_an_activity')</a>
                    </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{url('/lite/js/dashboard.js')}}"></script>
    <script src="{{url('/lite/js/lite.js')}}"></script>
    <script>
        
    </script>
@stop
