@extends('tz.base.sidebar')

@section('title', 'Project')
@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
        @include('includes.response')
        <div class="element-panel-heading">
            <div>
                <span>{{ $project->title ? $project->title[0]['narrative'] : 'No Title' }}</span>
                <div class="element-panel-heading-info">
                    <span>{{ $project->identifier['activity_identifier'] }}</span>
                    <span class="last-updated-date">Last Updated on: {{ changeTimeZone($project['updated_at'], 'M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
            <div class="activity-status activity-status-{{ $statusLabel[$activityWorkflow] }}">
                <ol>
                    @foreach($statusLabel as $key => $value)
                        @if($key == $activityWorkflow)
                            <li class="active"><span>{{ $value }}</span></li>
                        @else
                            <li><span>{{ $value }}</span></li>
                        @endif
                    @endforeach
                </ol>
                @include('tz.project.partials.workflow')
            </div>

            <a href="{{ route('change-project-defaults', $id) }}" class="pull-right">
                <span class="glyphicon glyphicon-triangle-left"></span>Override Activity Default
            </a>
            <div class="panel panel-default panel-element-detail element-show">
                <div class="panel-body">
                    <div class="panel-heading">
                        Identifier
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">IATI Identifier Text:</div>
                        <div class="col-xs-12 col-sm-8">{{$project->identifier['iati_identifier_text']}}</div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                        Title
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Text:</div>
                        <div class="col-xs-12 col-sm-8">{{$project->title[0]['narrative']}}</div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                        Description
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">General Description:</div>
                        <div class="col-xs-12 col-sm-8">
                            @if($project->description[0]['type'] == 1)
                                {{$project->description[0]['narrative'][0]['narrative']}}
                            @else
                                &nbsp;
                            @endif
                        </div>
                    </div>
                    @if($project->description[0]['type'] == 2)
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Objectives:</div>
                            <div class="col-xs-12 col-sm-8">
                                {{$project->description[0]['narrative'][0]['narrative']}}
                            </div>
                        </div>
                    @endif
                    @if($project->description[0]['type'] == 3)
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Target Groups:</div>
                            <div class="col-xs-12 col-sm-8">
                                {{$project->description[0]['narrative'][0]['narrative']}}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                        Project Status
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Status:</div>
                        <div class="col-xs-12 col-sm-8">
                            {{ $getCode->getActivityCodeName('ActivityStatus', $project->activity_status) }}
                        </div>
                    </div>
                </div>

                @if($getCode->getActivityCodeName('SectorCategory', $project->sector['sector_category_code']) != null)
                    <div class="panel-body">
                        <div class="panel-heading">
                            Sectors
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Sector:</div>
                            <div class="col-xs-12 col-sm-8">
                                {{ $getCode->getActivityCodeName('SectorCategory', $project->sector['sector_category_code'])}}
                            </div>
                        </div>
                    </div>
                @endif

                @if($project->activity_date[0]['type'] == 2)
                    <div class="panel-body">
                        <div class="panel-heading">
                            Start Date
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Date:</div>
                            <div class="col-xs-12 col-sm-8">
                                {{$project->activity_date[0]['date']}}
                            </div>
                        </div>
                    </div>
                @endif

                @if($project->activity_date[0]['type'] == 4)
                    <div class="panel-body">
                        <div class="panel-heading">
                            End Date
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-sm-4">Date:</div>
                            <div class="col-xs-12 col-sm-8">
                                {{$project->activity_date[0]['date']}}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="panel-body">
                    <div class="panel-heading">
                        Participating Organization
                    </div>
                    @if($project->participating_organization[0]['organization_role'] == "1")
                        <div class="col-xs-12 col-md-12">
                            <div class="panel-heading">
                                Funding:
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Organization Type:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$getCode->getActivityCodeName('OrganisationType', $project->participating_organization[0]['organization_type'])}}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($project->participating_organization[0]['organization_role'] == 4)
                        <div class="col-xs-12 col-md-12">
                            <div class="panel-heading">
                                Implementing:
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Organization Type:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$getCode->getActivityCodeName('OrganisationType', $project->participating_organization[0]['organization_type'])}}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                        Document Link
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Document URL:</div>
                        <div class="col-xs-12 col-sm-8">
                            {{$project->document_link[0]['url']}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Annual Report:</div>
                        <div class="col-xs-12 col-sm-8">
                            {{$getCode->getActivityCodeName('FileFormat', $project->documentLink[0]['format'])}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="col-xs-12 col-sm-4">Title:</div>
                        <div class="col-xs-12 col-sm-8">
                            {{$project->document_link[0]['title'][0]['narrative']}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                        Transactions
                    </div>
                    @foreach($transactions as $transaction)
                        <div class="col-xs-12 col-md-12">
                            <div class="panel-heading">
                                {{$getCode->getActivityCodeName('TransactionType', $transaction->transaction['transaction_type'][0]['transaction_type_code']) }}:
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Transaction Reference:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$transaction->transaction['reference']}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Transaction Date:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$transaction->transaction['transaction_date'][0]['date']}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Amount:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$transaction->transaction['value'][0]['amount']}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Currency:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$getCode->getCode('Organization', 'Currency', $transaction->transaction['value'][0]['currency'])}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Description:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$transaction->transaction['description'][0]['narrative'][0]['narrative']}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <div class="col-xs-12 col-sm-4">Provider Organization:</div>
                                <div class="col-xs-12 col-sm-8">
                                    {{$transaction->transaction['provider_organization'][0]['narrative'][0]['narrative']}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    </div>
@endsection
