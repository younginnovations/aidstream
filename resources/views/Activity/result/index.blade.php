@extends('app')

@section('title', 'Activity Results - ' . $activityData->IdentifierTitle)

@section('content')

<div class="container main-container">
    <div class="row">
        @include('includes.side_bar_menu')
        <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
            @include('includes.response')
            <div class="element-panel-heading">
                <div>Activity Results</div>

                @if(count($results) > 0)
                    <div class="panel-action-btn">
                        <a href="{{ route('activity.result.create', $id) }}" class="btn btn-primary pull-right add-new-btn">Add Another
                            Result</a>
                    </div>
                @endif
            </div>
            <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper result-content-wrapper">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(count($results) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">S.N.</th>
                                    <th width="45%">Title</th>
                                    <th width="30%">Type</th>
                                    <th width="15%">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $resultIndex=>$result)
                                    <tr data-href="{{ route('activity.result.show', [$id, $result->id]) }}" class="clickable-row">
                                        <td>{{ $resultIndex + 1 }}</td>
                                        <td class="activity_title">
                                            {{ $result->title }}
                                        </td>
                                        <td>
                                            {{ $result->type }}
                                        </td>
                                        <td>
                                            <div class="activity_actions">
                                                <a href="{{ route('activity.result.show', [$id, $result->id]) }}"
                                                   class="view">View</a>
                                                <a href="{{ route('activity.result.edit', [$id, $result->id]) }}"
                                                   class="edit">Edit</a>
                                                <a href="{{ url(sprintf('activity/%s/result/%s/delete', $id, $result->id)) }}"
                                                   class="delete">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center no-data no-result-data">
                                You haven’t added any result yet.
                                <a href="{{ route('activity.result.create', $id) }}" class="btn btn-primary">Add Another
                                    Result</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
</div>
@endsection
