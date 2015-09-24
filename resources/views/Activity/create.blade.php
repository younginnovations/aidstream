@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('trans.home')</div>

                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">@lang('trans.activity_default')</div>

                            <div class="panel-body">
                                <div>
                                    <div class="col-md-6">Default Language:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_language'] }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Default Currency:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_currency'] }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Hierarchy:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_hierarchy'] }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Reporting Organisation Identifier:</div>
                                    <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_identifier']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Reporting Organisation Type:</div>
                                    <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_type']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Reporting Organisation Name:</div>
                                    <div class="col-md-6">{{ $organization['name']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Reporting Organisation language:</div>
                                    <div class="col-md-6">{{ $organization['name']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Collaboration Type:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_collaboration_type']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Default Flow Type:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_flow_type']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Default Finance Type:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_finance_type']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Default Aid Type:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['default_aid_type']  }}</div>
                                </div>
                                <div>
                                    <div class="col-md-6">Default Tied Status:</div>
                                    <div class="col-md-6">{{ $defaultFieldValues[0]['Default_tied_status']  }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.side_bar_menu')
        </div>
    </div>
@endsection

