@extends('app')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
                <div class="panel panel-default panel-create">
                    <div class="panel-content-heading panel-title-heading">{{$duplicate ? 'Duplicate Activity' : 'Add Activity'}}</div>
                    <div class="panel-body">
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
                @if(!$duplicate)
                    <div class="panel panel-default panel-element-detail panel-activity-default">
                        <div class="panel-body">
                            <div class="alert alert-info">You can change the Activity Default Field Values once after you create an activity.</div>
                            <div class="panel-default">
                            <div class="panel-heading">@lang('trans.activity_default')</div>
                            <div class="panel-body panel-element-body">
                            <div class="col-md-6">
                                <div class="col-md-6">Default Language:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_language'] }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Default Currency:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_currency'] }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Hierarchy:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_hierarchy'] }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Reporting Organisation Identifier:</div>
                                <div class="col-md-6"
                                     id="reporting_organization_identifier">{{ $reportingOrganization[0]['reporting_organization_identifier']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Reporting Organisation Type:</div>
                                <div class="col-md-6">{{ $reportingOrganization[0]['reporting_organization_type']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Reporting Organisation Name:</div>
                                <div class="col-md-6">{{ $organization['name']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Reporting Organisation language:</div>
                                <div class="col-md-6">{{ $organization['name']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Collaboration Type:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_collaboration_type']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Default Flow Type:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_flow_type']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Default Finance Type:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_finance_type']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Default Aid Type:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_aid_type']  }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">Default Tied Status:</div>
                                <div class="col-md-6">{{ $defaultFieldValues[0]['default_tied_status']  }}</div>
                            </div>
                            <div class="col-md-6">
                            <div class="col-md-6">Linked Data uri:</div>
                            <div class="col-md-6">{{ $defaultFieldValues[0]['linked_data_uri']  }}</div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info"><span>You are duplicating activity:
                        Please note that Transactions and Results are not duplicated. You have to manually add Transactions and Results to the duplicated activity.</span></div>
                @endif
            </div>
        </div>
    </div>
@endsection

