@extends('app')

@section('title', 'Reporting Organization')
@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel-content-heading">
                    <div>Reporting Organization
                        <div class="panel-action-btn">
                            <a href="{{ route('activity.show', $id) }}" class="btn btn-primary">View Activity
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default panel-element-detail">
                        <div class="panel-body">
                            <div class="panel-default">
                                <div class="panel-body panel-element-body">
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-12 col-sm-4 col-lg-3">Identifier:</div>
                                        <div class="col-xs-12 col-sm-8 col-lg-9">{{ $reportingOrganization[0]['reporting_organization_identifier'] }}</div>
                                    </div>
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-12 col-sm-4 col-lg-3">Type:</div>
                                        <div class="col-xs-12 col-sm-8 col-lg-9">{{ $getCode->getOrganizationCodeName('OrganizationType', $reportingOrganization[0]['reporting_organization_type']) }}</div>
                                    </div>
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-12 col-sm-4 col-lg-3">Name:</div>
                                        <div class="col-xs-12 col-sm-8 col-lg-9">
                                            {{--*/ $narratives = [] /*--}}
                                            @foreach($reportingOrganization[0]['narrative'] as $narrative)
                                                {{--*/ $narratives[] = $narrative['narrative'] . ($narrative['language'] ? '[' . $narrative['language'] . ']' : '') /*--}}
                                            @endforeach
                                            {{ implode('<br />', $narratives) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="activity-description"><span>Reporting organization information can be updated in <a href="{{ route('settings') }}">Settings</a>.</span></div>
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
