@extends('tz.base.sidebar')

@section('title', 'Create Activity')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Add a new Project</div>
            </div>
            <div class="panel-body">
                <div class="create-activity-form">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['method' => 'post', 'route' => 'project.store', 'role' => 'form', 'id' => 'project-form']) !!}
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
                                        {!! Form::label('objectives', 'Objectives', ['class' => 'control-label required']) !!}
                                        {!! Form::text('objectives', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::label('target_groups', 'Target Groups', ['class' => 'control-label required']) !!}
                                        {!! Form::text('target_groups', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::label('activity_status', 'Project Status', ['class' => 'control-label required']) !!}
                                        {!! Form::select('activity_status', ['' => 'Select one of the following.'] + $codeList, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('sector', 'Sector', ['class' => 'control-label required']) !!}
                                        {!! Form::select('sector', ['' => 'Select one of the following.'] + $sectors, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::label('start_date', 'Start Date', ['class' => 'control-label required']) !!}
                                        {!! Form::text('start_date', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('end_date', 'End Date', ['class' => 'control-label required']) !!}
                                        {!! Form::text('end_date', null, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        {!! Form::label('recipient_country', 'Project Country', ['class' => 'control-label required']) !!}
                                        {!! Form::select('recipient_country', ['' => 'Select one of the following.'] + $recipientCountries, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('recipient_region', 'Recipient Region', ['class' => 'control-label required']) !!}
                                        {!! Form::select('recipient_region', ['' => 'Select one of the following.'] + $recipientRegions, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12" id="funding-wrap">
                                    <h2>Funding</h2>

                                    <div class="col-sm-6">
                                        {!! Form::label('funding_organization[0][funding_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
                                        {!! Form::text('funding_organization[0][funding_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('funding_organization[0][funding_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
                                        {!! Form::select('funding_organization[0][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                    <button type="button" id="add-more-funding-organization" class="add-more">Add More Funding Organization</button>
                                </div>

                                <div class="col-sm-12" id="implementing-wrap">
                                    <h2>Implementing</h2>
                                    <div class="col-sm-6">
                                        {!! Form::label('implementing_organization[0][implementing_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
                                        {!! Form::text('implementing_organization[0][implementing_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('implementing_organization[0][implementing_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
                                        {!! Form::select('implementing_organization[0][implementing_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                    <button type="button" id="add-more-implementing-organization" class="add-more">Add More Implementing Organization</button>
                                </div>

                                <div class="col-sm-12">
                                    <h2>Results/Outcomes Documents</h2>
                                    <div class="col-sm-6">
                                        {!! Form::label('result_document_title', 'Title', ['class' => 'control-label required']) !!}
                                        {!! Form::text('result_document_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('result_document_url', 'Document URL', ['class' => 'control-label required']) !!}
                                        {!! Form::text('result_document_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <h2>Annual Reports</h2>
                                    <div class="col-sm-6">
                                        {!! Form::label('annual_document_title', 'Title', ['class' => 'control-label required']) !!}
                                        {!! Form::text('annual_document_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('annual_document_url', 'Document Url', ['class' => 'control-label required']) !!}
                                        {!! Form::text('annual_document_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::submit('Create Project', ['class' => 'btn btn-primary btn-create pull-right']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="hidden" id="funding-org">
                                <div class="col-sm-6">
                                    {!! Form::label('funding_organization[index][funding_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
                                    {!! Form::text('funding_organization[index][funding_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('funding_organization[index][funding_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
                                    {!! Form::select('funding_organization[index][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <a href="javascript:void(0)" onclick="removeFunding(this)">Remove</a>
                            </div>

                            <div class="hidden" id="implementing-org">
                                <div class="col-sm-6">
                                    {!! Form::label('implementing_organization[index][implementing_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
                                    {!! Form::text('implementing_organization[index][implementing_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('implementing_organization[index][implementing_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
                                    {!! Form::select('implementing_organization[index][implementing_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>

                                <a href="javascript:void(0)" onclick="removeImplementing(this)">Remove</a>
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
