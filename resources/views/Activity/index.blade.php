@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activities</div>

                    <div class="panel-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>S.N.</th>
                                <th>Title</th>
                                <th>Activity Identifier</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $status_label = ['Draft', 'Completed', 'Verified', 'Published'];
                            ?>
                            @forelse($activities as $key=>$activity)
                                <tr>
                                    <td><input type="checkbox"/></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="activity_title">
                                        {{ $activity->title ? $activity->title[0]['narrative'] : 'No Title' }}
                                        <div class="activity_actions">
                                            <a href="{{ route('activity.show', [$activity->id]) }}">View</a>
                                            <a href="{{ route('activity.destroy', [$activity->id]) }}">Delete</a>
                                        </div>
                                    </td>
                                    <td>{{ $activity->identifier['activity_identifier'] }}</td>
                                    <td>{{ $activity->updated_at }}</td>
                                    <td>{{ $status_label[$activity->activity_workflow] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No activities found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            @include('includes.side_bar_menu')
        </div>
    </div>
@endsection

