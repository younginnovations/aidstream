{{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
{{ header("Pragma: no-cache") }}
{{ header("Expires: 0 ")}}

@extends('app')

@section('title', 'Activities')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
                @include('includes.response')
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                @include('includes.breadcrumb')
                <div id="xml-import-status-placeholder"></div>
                <div class="panel panel-default">
                    <div class="panel-content-heading">
                        <div>Activities</div>
                    </div>
                    <div class="panel-body">
                        @if(count($activities) > 0)
                            <table class="table table-striped" id="data-table">
                                <thead>
                                <tr>
                                    <th width="20px" class="no-sort">S.N.</th>
                                    <th width="45%">Activity Title</th>
                                    <th class="default-sort">Last Updated</th>
                                    <th class="status">Status</th>
                                    <th class="no-sort" style="width:100px!important">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $status_label = ['draft', 'completed', 'verified', 'published'];
                                ?>
                                @foreach($activities as $key=>$activity)
                                    <tr class="clickable-row" data-href="{{ route('activity.show', [$activity->id]) }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td class="activity_title">
                                            {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                            <i class="{{ $activity->isImportedFromXml() ? 'imported-from-xml' : '' }}">icon</i>
                                            <span>{{ $activity->identifier['activity_identifier'] }}</span>
                                        </td>
                                        <td class="updated-date">{{ changeTimeZone($activity->updated_at) }}</td>
                                        <td>
                                            <span class="{{ $status_label[$activity->activity_workflow] }}">{{ $status_label[$activity->activity_workflow] }}</span>
                                            @if($activity->activity_workflow == 3)
                                                <div class="popup-link-content">
                                                    <a href="#" title="{{ucfirst($activityPublishedStats[$activity->id])}}"
                                                       class="{{ucfirst($activityPublishedStats[$activity->id])}}">{{ucfirst($activityPublishedStats[$activity->id])}}</a>
                                                    <div class="link-content-message">
                                                        {!!$messages[$activity->id]!!}
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('activity.show', [$activity->id]) }}" class="view"></a>
                                            <a href="{{ url(sprintf('activity/%s/delete', $activity->id)) }}"
                                               class="delete">Delete</a>
                                            <a href="{{ route('activity.duplicate', [$activity->id]) }}"
                                               class="duplicate">Duplicate</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-activity-data">
                                <p>You haven’t added any activity yet.</p>
                                <a href="{{route('activity.create') }}" class="btn btn-primary">Add an activity</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    @if(session('xml_import_status'))
        <script type="text/javascript" src="{{asset('js/xmlImporter/xmlImporter.js')}}"></script>
    @endif
    {{--<script>--}}
    {{--$(document).ready(function () {--}}
    {{--$('[data-toggle="popover"]').popover({html: true});--}}
    {{--});--}}
    {{--g--}}
    {{--</script>--}}
@endsection