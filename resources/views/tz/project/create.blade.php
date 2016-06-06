@extends('tz.base.sidebar')

@section('title', 'Create Activity')
@inject('code', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="panel panel-default panel-create">
            <div class="panel-content-heading panel-title-heading">
                <div>Add Project</div>
            </div>
            <div class="panel-body">
                <div class="create-form create-project-form">
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
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('objectives', 'Objectives', ['class' => 'control-label']) !!}
                                {!! Form::textarea('objectives', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('target_groups', 'Target Groups', ['class' => 'control-label']) !!}
                                {!! Form::textarea('target_groups', null, ['class' => 'form-control']) !!}
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
                                {!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}
                                {!! Form::text('end_date', null, ['class' => 'form-control datepicker']) !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                {!! Form::label('recipient_country', 'Project Country', ['class' => 'control-label required']) !!}
                                {!! Form::select('recipient_country', ['' => 'Select one of the following.'] + $recipientCountries, 'TZ', ['class' => 'form-control', 'required' => 'required', 'id' => 'project-country']) !!}
                            </div>
                        </div>
                        @include('tz.project.partials.location')
                        <div class="col-sm-12 add-wrap" id="funding-wrap">
                            <h2>Funding Organisation</h2>
                            <div class="col-sm-6">
                                {!! Form::label('funding_organization[0][funding_organization_name]', 'Organization Name', ['class' => 'control-label']) !!}
                                {!! Form::text('funding_organization[0][funding_organization_name]', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('funding_organization[0][funding_organization_type]', 'Organization Type', ['class' => 'control-label']) !!}
                                {!! Form::select('funding_organization[0][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control']) !!}
                            </div>
                            <button type="button" id="add-more-funding-organization" class="add-more">Add Another Funding
                                Organization
                            </button>
                        </div>
                        <div class="col-sm-12 add-wrap" id="implementing-wrap">
                            <h2>Implementing Organisation</h2>
                            <div class="col-sm-6">
                                {!! Form::label('implementing_organization[0][implementing_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
                                {!! Form::text('implementing_organization[0][implementing_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('implementing_organization[0][implementing_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
                                {!! Form::select('implementing_organization[0][implementing_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            <button type="button" id="add-more-implementing-organization" class="add-more">Add Another
                                Implementing Organization
                            </button>
                        </div>

                        <div class="col-sm-12 add-wrap">
                            <h2>Results/Outcomes Documents</h2>
                            {!! Form::hidden('document_link[0][category][0][code]', 'A08') !!}
                            {!! Form::hidden('document_link[0][format]', 'text/html') !!}
                            {!! Form::hidden('document_link[0][title][0][narrative][0][language]', "") !!}
                            {!! Form::hidden('document_link[0][language]', '[]') !!}

                            <div class="col-sm-6">
                                {!! Form::label('result_document_title', 'Title', ['class' => 'control-label']) !!}
                                {!! Form::text('document_link[0][title][0][narrative][0][narrative]', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('result_document_url', 'Document URL', ['class' => 'control-label']) !!}
                                {!! Form::text('document_link[0][url]', null, ['class' => 'form-control']) !!}
                                <span>Example: http://example.com</span>
                            </div>
                        </div>

                        <div class="col-sm-12 add-wrap">
                            <h2>Annual Reports</h2>
                            {!! Form::hidden('document_link[1][category][0][code]', 'B01') !!}
                            {!! Form::hidden('document_link[1][format]', 'text/html') !!}
                            {!! Form::hidden('document_link[1][title][0][narrative][0][language]', "") !!}
                            {!! Form::hidden('document_link[1][language]', '[]') !!}
                            <div class="col-sm-6">
                                {!! Form::label('annual_document_title', 'Title', ['class' => 'control-label']) !!}
                                {!! Form::text('document_link[1][title][0][narrative][0][narrative]', null, ['class' => 'form-control',]) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! Form::label('annual_document_url', 'Document URL', ['class' => 'control-label']) !!}
                                {!! Form::text('document_link[1][url]', null, ['class' => 'form-control']) !!}
                                <span>Example: http://example.com</span>
                            </div>
                        </div>
                    </div>
                    {!! Form::submit('Create Project', ['class' => 'btn btn-primary btn-form btn-create']) !!}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="hidden" id="funding-org">
                <div class="col-sm-6">
                    {!! Form::label('funding_organization[index][funding_organization_name]', 'Organization Name', ['class' => 'control-label']) !!}
                    {!! Form::text('funding_organization[index][funding_organization_name]', null, ['class' => 'form-control']) !!}
                </div>

                <div class="col-sm-6">
                    {!! Form::label('funding_organization[index][funding_organization_type]', 'Organization Type', ['class' => 'control-label']) !!}
                    {!! Form::select('funding_organization[index][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control']) !!}
                </div>
                <a href="javascript:void(0)" onclick="removeFunding(this)" class="remove_from_collection">Remove</a>
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

                <a href="javascript:void(0)" onclick="removeImplementing(this)" class="remove_from_collection">Remove</a>
            </div>

            @include('tz.project.partials.location-clone')
            @include('tz.project.partials.tz-location-clone')
        </div>
    </div>
@stop

@section('script')
    <script>
        var districts = {!! json_encode(config('tz.location.district')) !!};
        var oldLocationCount = 0;

        @if (old('location'))
            oldLocationCount = "{{ count(old('location')) - 1 }}";
        @else
            oldLocationCount = 0;
        @endif
    </script>
    <script src="{{ asset('/js/tz/project.js') }}"></script>
@stop
