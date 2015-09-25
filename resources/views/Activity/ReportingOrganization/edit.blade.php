@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Reporting Organization</div>

                    <div class="panel-body">
                        <div class="clearfix">
                            <div class="col-md-6">Reporting Organisation Identifier:</div>
                            <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_identifier'] }}</div>
                        </div>
                        <div class="clearfix">
                            <div class="col-md-6">Reporting Organisation Type:</div>
                            <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_type'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
