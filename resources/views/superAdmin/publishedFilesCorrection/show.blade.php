@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.response')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="element-panel-heading">
                    <h3>Published Files Correction For <strong>{{ $organization->name }}</strong></h3>
                    <small>
                        <small>
                            <div>
                                <b>Publishing Type</b>: {{ $settings->publishing_type }}<br/>
                                <b>Registry Info</b>: <br><i>Publisher Id</i>: {{ $settings->registry_info[0]['publisher_id'] }} <br/> <i>Api Id</i>: {{ $settings->registry_info[0]['api_id'] }}<br>
                                <b>Auto Publish</b>: {{ $settings->registry_info[0]['publish_files'] }}<br>
                                <b>Current Version</b>: {{ $settings->version }}

                            </div>
                        </small>
                    </small>
                    <img class="pull-right" src="{{ $organization->logo_url }}" alt="Organization Logo" width="100" height="100">
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    @include('superAdmin.publishedFilesCorrection.partials.activityFileCorrection')
                    <hr>
                    @include('superAdmin.publishedFilesCorrection.partials.organizationFileCorrection')
                </div>
            </div>
        </div>
    </div>
@stop
