@extends('np.base.sidebar')

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
                        @if($lastPublishedToIATI)
                            @lang('lite/activityDashboard.last_published_to_iati')
                            : {{substr(changeTimeZone($lastPublishedToIATI),0,12)}}
                        @endif
                    </i>
                    <p>
                        @lang('lite/activityDashboard.find_activities_and_stats')
                    </p>
                </div>
            </div>
            <div class="panel__body">
                @if(count($activities) > 0)
                    @include('lite.activity.activityStats')
                    <div class="sort-by-wrap pull-right">
                        <select id="sortBy">
                            <option>Sort By</option>
                            <option value="1">@lang('lite/activityDashboard.title')</option>
                            <option value="2">@lang('lite/activityDashboard.status')</option>
                            <option value="3">@lang('lite/activityDashboard.date')</option>
                        </select>
                    </div>
                    <table class="panel__table no-header-table" id="dataTable">
                        <thead>
                        <tr>
                            <th class="hidden"></th>
                            <th class="hidden" width="45%">@lang('lite/global.activity_title')</th>
                            <th class="default-sort hidden">@lang('lite/global.last_updated')</th>
                            <th class="status hidden">@lang('lite/global.status')</th>
                            <th class="no-sort hidden" style="width:100px!important">@lang('lite/global.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $status_label = ['draft', 'completed', 'verified', 'published'];
                        ?>
                        @foreach($activities as $key=>$activity)
                            <tr class="clickable-row" data-href="{{ route('np.activity.show', [$activity->id]) }}">
                                {{--<td>{{ $key + 1 }}</td>--}}
                                @if(!isMunicipalityAdmin())
                                <td class="activity_edit"><a href="{{ route('np.activity.edit', [$activity->id]) }}"
                                                             class="edit-activity"></a></td>
                                @endif
                                <td class="activity_title">
                                    {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                    {{--<i class="{{ $activity->isImportedFromXml() ? 'imported-from-xml' : '' }}">icon</i>--}}
                                    {{--<span>{{ $activity->identifier['activity_identifier'] }}</span>--}}
                                </td>
                                <td class="updated-date">{{ substr(changeTimeZone($activity->updated_at),0,12) }}</td>
                                <td>
                                    <span class="{{ $status_label[$activity->activity_workflow] }}">{{ $status_label[$activity->activity_workflow] }}</span>
                                    {{--@if($activity->activity_workflow == 3)--}}
                                    {{--<div class="popup-link-content">--}}
                                    {{--<a href="#" title="{{ucfirst($activityPublishedStats[$activity->id])}}" class="{{ucfirst($activityPublishedStats[$activity->id])}}">{{ucfirst($activityPublishedStats[$activity->id])}}</a>--}}
                                    {{--<div class="link-content-message">--}}
                                    {{--{!!$messages[$activity->id]!!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                </td>
                                <td>
                                    {{--<a href="{{ route('lite.activity.show', [$activity->id]) }}" class="view"></a>--}}

                                    {{--Use Delete Form to delete--}}
                                    {{--<a href="{{ url(sprintf('/lite/activity/%s/delete', $activity->id)) }}" class="delete">Delete</a>--}}
                                    <div class="view-more">
                                        <a href="#">&ctdot;</a>
                                        <div class="view-more-actions">
                                            <ul>
                                                <li><a href="{{ route('np.activity.edit', [$activity->id]) }}" class="edit-activity">@lang('lite/global.edit_activity')</a></li>
                                                <li>
                                                    <a href="{{ route('np.activity.duplicate.edit', $activity->id) }}" class="duplicate-activity">@lang('lite/global.duplicate_activity')</a>
                                                </li>
                                                <li>
                                                    <a data-toggle="modal" data-target="#delete-modal" data-href="{{ route('np.activity.delete') }}"
                                                       data-index="{{ $activity->id }}" data-message="@lang('lite/global.confirm_delete')"
                                                       class="delete-activity delete-confirm">@lang('lite/global.delete_activity')</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center no-data no-activity-data">
                        <p>@lang('lite/global.not_added',['type' => trans('global.activity')]))</p>
                        <a href="{{route('np.activity.create') }}"
                           class="btn btn-primary">@lang('lite/global.add_an_activity')</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{url('/np/js/dashboard.js')}}"></script>
    <script src="{{url('/np/js/lite.js')}}"></script>
    <script>
        $(document).ready(function () {
            var data = [{!! implode(",",$stats) !!}];
            var totalActivities = {!! count($activities) !!}
            Dashboard.init(data, totalActivities);

            var searchPlaceholder = "{{trans('lite/activityDashboard.type_an_activity_title_to_search')}}";
            Lite.dataTable(searchPlaceholder);

            var ajaxRequest = Lite.budgetDetails();

            $('a').on('click', function (e) {
                if (ajaxRequest && ajaxRequest.readyState != 4) {
                    ajaxRequest.abort();
                }
            });
        });
    </script>
@stop
