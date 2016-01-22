@extends('app')

@section('title', 'Reporting Organization')

@section('content')
    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel-content-heading">Reporting Organization</div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-element-detail">
                        <div class="panel-body">
                            <div class="panel-default">
                                <div class="panel-body panel-element-body">
                                    <div class="col-md-12 clearfix">
                                        <div class="col-md-6">Reporting Organisation Identifier:</div>
                                        <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_identifier'] }}</div>
                                    </div>
                                    <div class="col-md-12 clearfix">
                                        <div class="col-md-6">Reporting Organisation Type:</div>
                                        <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_type'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
        </div>
    </div>
    </div>
@endsection
