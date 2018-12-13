@extends('np.municipalityAdmin.includes.base')

@section('title', trans('np/title.activities'))

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading dashboard-panel__heading">
                <div>
                    <div class="panel__title">@lang('lite/activityDashboard.activities')</div>
                </div>
            </div>
            <div class="panel__body">
                @if(count($activitiesList) > 0)
                    <table class="panel__table no-header-table" id="dataTable">
                        <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="45%">@lang('lite/global.activity_title')</th>
                            <th class="default-sort">@lang('lite/global.last_updated')</th>
                            <th class="status">@lang('lite/global.status')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $status_label = ['draft', 'completed', 'verified', 'published'];
                        ?>
                        @foreach($activitiesList as $key => $activity)
                            <tr class="clickable-row" data-href="{{route('municipalityAdmin.activityShow', $activity->id) }}">
                                <td>{{$key+1}}</td>
                                <td class="activity_title">
                                    {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                </td>
                                <td class="updated-date">{{ substr(changeTimeZone($activity->updated_at),0,12) }}</td>
                                <td>
                                    <span class="{{ $status_label[$activity->activity_workflow] }}">{{ $status_label[$activity->activity_workflow] }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center no-data no-activity-data">
                        <p>@lang('np/municipalityDashboard.no_activity')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{url('/np/js/dashboard.js')}}"></script>
    <script src="{{url('/np/js/np.js')}}"></script>
    <script>
        $(document).ready(function () {
            var data = [{!! implode(",",$stats) !!}];
            var totalActivities = {!! count($activitiesList) !!}
            Dashboard.init(data, totalActivities);

            var searchPlaceholder = "{{trans('lite/activityDashboard.type_an_activity_title_to_search')}}";
            Np.dataTable(searchPlaceholder);

            var ajaxRequest = Np.budgetDetails();

            $('a').on('click', function (e) {
                if (ajaxRequest && ajaxRequest.readyState != 4) {
                    ajaxRequest.abort();
                }
            });
            $('#dataTable_filter>label').css('padding-top','80px');
        });
    </script>
@stop
