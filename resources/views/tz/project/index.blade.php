@extends('tz.base.sidebar')

@section('content')
    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div class="panel panel-default">
            <div class="panel-content-heading">
                <div>Projects</div>
            </div>
            <div class="panel-body">
                <table class="table table-striped" id="data-table">
                    <thead>
                    <tr>
                        <th width="20px" class="no-sort">S.N.</th>
                        <th width="50%">Project Title</th>
                        <th class="default-sort">Last Updated</th>
                        <th class="status">Status</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($projects as $key => $project)
                        <tr class="clickable-row" data-href="{{ route('project.show', [$project->id]) }}">
                            <td>{{ $key + 1 }}</td>
                            <td class="activity_title">
                                {{ $project->title ? $project->title[0]['narrative'] : 'No Title' }} <span>{{ $project->identifier['activity_identifier'] }}</span>
                            </td>
                            <td class="updated-date">{{ changeTimeZone($project->updated_at) }}</td>
                            <td>
                                <span class="{{ $statusLabel[$project->activity_workflow] }}">{{ $statusLabel[$project->activity_workflow] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('project.show', [$project->id]) }}" class="view"></a>
                                <a href="javascript:void(0)" class="delete-project" data-route="{{ route('project.destroy', [$project->id]) }}">Delete</a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.destroy', $project->id], 'class' => 'hidden', 'role' => 'form', 'id' => 'project-delete-form']) !!}
                                {!! Form::submit('Delete') !!}
                                {!! Form::close() !!}
                                <a href="javascript:void(0)" class="duplicate" id="duplicate-project">Duplicate</a>
                                {!! Form::open(['method' => 'POST', 'route' => ['project.duplicate', $project->id], 'class' => 'hidden', 'role' => 'form', 'id' => 'project-duplicate-form']) !!}
                                {!! Form::submit('Duplicate') !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center no-data no-activity-data"> You haven’t added any Projects yet.
                                    <a href="{{ route('project.create') }}" class="btn btn-primary">Add a Project</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="hidden">
        <div class="modal" id="projectDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Confirm Delete?
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this project?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn_del" type="button" id="yes-delete">Yes</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">No</button>
                        ;
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('/js/tz/project.js') }}"></script>
@stop
