@extends('app')

@section('title', 'Activities')

@section('content')

    {{Session::get('message')}}

    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">Activities</div>
                    <div class="panel-body">
                        @if(count($activities) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th width="50px">S.N.</th>
                                    <th>Activity Title</th>
                                    <th>Activity Identifier</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $status_label = ['draft', 'completed', 'verified', 'published'];
                                ?>
                                @foreach($activities as $key=>$activity)
                                    <tr>
                                        <td><input type="checkbox"/></td>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="activity_title">
                                            {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                        </td>
                                        <td>{{ $activity->identifier['activity_identifier'] }}</td>
                                        <td class="updated-date">{{changeTimeZone('GMT', Auth::user()->time_zone, $activity->updated_at)}}</td>
                                        <td><span class="{{ $status_label[$activity->activity_workflow] }}">{{ $status_label[$activity->activity_workflow] }}</span></td>
                                        <td>
                                            <a href="{{ route('activity.show', [$activity->id]) }}" class="view">View</a>
                                            <a href="{{ url(sprintf('activity/%s/delete', $activity->id)) }}" class="delete">Delete</a>
                                            <a href="{{ route('activity.duplicate', [$activity->id]) }}" class="duplicate">Duplicate</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-activity-data">
                                You haven’t added any activity yet.
                                <a href="{{route('activity.create') }}" class="btn btn-primary">Add an activity</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
