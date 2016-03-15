@extends('app')

@section('title', 'Activity Identifier')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel-content-heading panel-title-heading">
                    <div>Activity Identifier</div>
                    <div class="pull-right panel-action-btn">
                        <a href="{{ route('activity.show', $id) }}" class="btn btn-primary">View Activity
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="hidden"
                                 id="reporting_organization_identifier">{{ $reportingOrganization[0]['reporting_organization_identifier']  }}</div>
                            <div class="create-activity-form">
                                {!! form_start($form) !!}
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        {!! form_rest($form) !!}
                                    </div>
                                </div>
                                {!! form_end($form) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
@endsection


