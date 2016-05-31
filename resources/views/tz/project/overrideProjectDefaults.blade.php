@extends('tz.base.sidebar')

@section('title', 'Project Defaults')

@section('content')
        <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
            @include('includes.response')
            <div class="panel-content-heading panel-title-heading panel-change-activity-heading">Project Default Values</div>
            <div class="panel panel-default panel-create">
                <div class="panel-body">
                    <div class="create-form change-activity-form">
                        {!! Form::model($project, ['route' => ['project.override-project-default', $project['id']], 'method' => 'patch']) !!}
                        <div class="col-md-6">
                            {!! Form::label('default_currency', 'Default Currency', ['class' => 'control-label required']) !!}
                            {!! Form::select('default_currency', ['' => 'Select one of following:'] + $currency, $project['default_currency'],['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('default_language', 'Default Language', ['class' => 'control-label required']) !!}
                            {!! Form::select('default_language', ['' => 'Select one of following:'] + $language, $project['default_language'],['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <a class="btn btn-cancel" href="{{ route('project.show', $project['id']) }}">Cancel</a>
                        {!! Form::submit('Save', ['class' => 'btn btn-submit btn-form']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
@stop

