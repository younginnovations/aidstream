@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.response')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper correct-published-files-wrapper">
                <div class="element-panel-heading">
                    <img class="pull-left" src="{{ $organization->logo_url }}" alt="Organisation Logo" width="100"
                         height="100">
                    <div class="pull-left correction-heading-wrap">
                        <h3>Published Files Correction For <strong>{{ $organization->name }}</strong></h3>
                        <div class="published-files-info">
                            <ul>
                                <li><label>Publishing Type:</label>{{ $settings->publishing_type }}</li>
                                <li><label>Registry Info:</label></li>
                                <li><label>Publisher Id:</label>{{ $settings->registry_info[0]['publisher_id'] }}</li>
                                <li><label>Api Id:</label>{{ $settings->registry_info[0]['api_id'] }}</li>
                                <li><label>Auto Publish:</label>{{ $settings->registry_info[0]['publish_files'] }}</li>
                                <li><label>Current Version:</label>{{ $settings->version }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    @include('superAdmin.publishedFilesCorrection.partials.activityFileCorrection')
                    @include('superAdmin.publishedFilesCorrection.partials.organizationFileCorrection')
                </div>
            </div>
        </div>
    </div>
@stop
