@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel panel-default">
                    <div class="panel-content-heading">Activities</div>
                    <div class="panel-body">
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
                            @forelse($activities as $key=>$activity)
                                <tr>
                                    <td><input type="checkbox"/></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="activity_title">
                                        {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                    </td>
                                    <td>{{ $activity->identifier['activity_identifier'] }}</td>
                                    <td class="updated-date">{{ $activity->updated_at }}</td>
                                    <td><span class="{{ $status_label[$activity->activity_workflow] }}">{{ $status_label[$activity->activity_workflow] }}</span></td>
                                    <td><a href="{{ route('activity.show', [$activity->id]) }}" class="view">View</a><a href="{{ route('activity.destroy', [$activity->id]) }}" class="delete">Delete</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center no-data">You haven’t added an activity yet. </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

