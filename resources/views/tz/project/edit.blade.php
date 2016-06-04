@extends('tz.base.sidebar')

@section('title', 'Edit Activity')

@inject('code', 'App\Helpers\GetCodeName')

@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Edit Project</div>
            </div>
            <div class="panel-body">
                <div class="create-form create-project-form edit-form">
                    {!! Form::model($project, ['method' => 'patch', 'route' => ['project.update', $project['id']], 'role' => 'form']) !!}
                    <input type="hidden" name="organization_id" value="{{ session('org_id') }}">
                    <div id="basic-info">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('identifier', 'Project Identifier', ['class' => 'control-label required']) !!}
                                {!! Form::text('identifier', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('title', 'Project Title', ['class' => 'control-label required']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('description', 'General Description', ['class' => 'control-label required']) !!}
                                {!! Form::text('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('objectives', 'Objectives', ['class' => 'control-label']) !!}
                                {!! Form::text('objectives', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('target_groups', 'Target Groups', ['class' => 'control-label']) !!}
                                {!! Form::text('target_groups', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        @include('tz.project.partials.budget', ['edit' => true])

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('activity_status', 'Project Status', ['class' => 'control-label required']) !!}
                                {!! Form::select('activity_status', ['' => 'Select one of the following.'] + $codeList, $project['activity_status'], ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('sector', 'Sector', ['class' => 'control-label required']) !!}
                                {!! Form::select('sector', ['' => 'Select one of the following.'] + $sectors, $project['sector'], ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('start_date', 'Start Date', ['class' => 'control-label required']) !!}
                                {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}
                                {!! Form::text('end_date', null, ['class' => 'form-control datepicker']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('recipient_country', 'Project Country', ['class' => 'control-label required']) !!}
                                {!! Form::select('recipient_country', ['' => 'Select one of the following.'] + $recipientCountries, $project['recipient_country'], ['class' => 'form-control', 'required' => 'required', 'id' => 'project-country-edit']) !!}
                            </div>
                        </div>

                        @include('tz.project.partials.location', ['multiple' => true])
                        @include('tz.project.partials.participating-organization', ['multiple' => true])
                        @include('tz.project.partials.document-link', ['edit' => true])
                    </div>
                    {!! Form::submit('Edit Project', ['class' => 'btn btn-primary btn-form']) !!}
                    {!! Form::close() !!}
                    @include('tz.project.partials.funding-clone')
                    @include('tz.project.partials.implementing-clone')
                    @include('tz.project.partials.location-edit-clone')
                    @include('tz.project.partials.tz-location-clone')

                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var countryChosen = "{{ $project['recipient_country'] }}";
        var currentLocationCount = "{{ count($project['location']) - 1 }}"
        var districts = {!! json_encode(config('tz.location.district')) !!};
    </script>
    <script src="{{ asset('/js/tz/project.js') }}"></script>
    <script src="{{ asset('/js/tz/editProject.js') }}"></script>
@stop
