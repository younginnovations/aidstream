@extends('tz.base.sidebar')

@section('title', 'Create Activity')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Add a new Project</div>

                {{--<div>{{($duplicate) ? 'Duplicate Activity' : 'Add Activity'}}</div>--}}
            </div>
            <div class="panel-body">
                <div class="create-activity-form">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['method' => 'post', 'route' => 'project.store', 'role' => 'form']) !!}
                            <input type="hidden" name="organization_id" value="{{ session('org_id') }}">
                            <div id="basic-info">
                                <div class="col-sm-6">
                                    {!! Form::label('identifier', 'Project Identifier', ['class' => 'control-label required']) !!}
                                    {!! Form::text('identifier', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('title', 'Project Title', ['class' => 'control-label required']) !!}
                                    {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('description', 'General Description', ['class' => 'control-label required']) !!}
                                    {!! Form::text('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('objectives', 'Objectives', ['class' => 'control-label required']) !!}
                                    {!! Form::text('objectives', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('target_groups', 'Target Groups', ['class' => 'control-label required']) !!}
                                    {!! Form::text('target_groups', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('activity_status', 'Project Status', ['class' => 'control-label required']) !!}
                                    {!! Form::select('activity_status', ['' => 'Select one of the following.'] + $codeList, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('sector', 'Sector', ['class' => 'control-label required']) !!}
                                    {!! Form::select('sector', ['' => 'Select one of the following.'] + $sectors, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('start_date', 'Start Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text('start_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('start_date', 'Start Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text('start_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('end_date', 'End Date', ['class' => 'control-label required']) !!}
                                    {!! Form::text('end_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('recipient_country', 'Project Country', ['class' => 'control-label required']) !!}
                                    {!! Form::select('recipient_country', ['' => 'Select one of the following.'] + $recipientCountries, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('recipient_region', 'Recipient Region', ['class' => 'control-label required']) !!}
                                    {!! Form::select('recipient_region', ['' => 'Select one of the following.'] + $recipientRegions, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <a class="btn btn-primary btn-sm pull-right" id="projectNextStep" href="javascript:void(0)">
                                    Next
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                </a>
                            </div>

                            <div id="other-info" class="hidden">
                                <div class="col-sm-6">
                                    {!! Form::label('transaction', 'Transaction', ['class' => 'control-label required']) !!}
                                    {!! Form::text('transaction', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('abc', 'Project Title', ['class' => 'control-label required']) !!}
                                    {!! Form::text('abc', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <a class="btn btn-primary btn-sm pull-left" id="projectPreviousStep" href="javascript:void(0)">
                                    Back
                                    <span class="glyphicon glyphicon-arrow-left"></span>
                                </a>

                                {!! Form::submit('Create Project', ['class' => 'btn btn-primary btn-create pull-right']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('/js/tz/project.js') }}"></script>
@stop
