@extends('tz.base.sidebar')

@section('title', 'Project')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="element-panel-heading">
            <div>
                <span>{{ $project->title ? $project->title[0]['narrative'] : 'No Title' }}</span>
                <div class="element-panel-heading-info">
                    <span>{{ $project->identifier['activity_identifier'] }}</span>
                    <span class="last-updated-date">Last Updated on: {{ changeTimeZone($project['updated_at'], 'M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
            <div class="activity-status activity-status-{{ $statusLabel[$activityWorkflow] }}">
                <ol>
                    @foreach($statusLabel as $key => $value)
                        @if($key == $activityWorkflow)
                            <li class="active"><span>{{ $value }}</span></li>
                        @else
                            <li><span>{{ $value }}</span></li>
                        @endif
                    @endforeach
                </ol>
                @include('tz.project.partials.workflow')
            </div>

            <a href="{{ route('change-project-defaults', $id) }}" class="pull-right">
                <span class="glyphicon glyphicon-triangle-left"></span>Override Activity Default
            </a>
            <div class="panel panel-default panel-element-detail element-show">
                <div class="panel-body">
                </div>
            </div>
        </div>

        {{--@include('includes.activity.element_menu')--}}
    </div>
@endsection


