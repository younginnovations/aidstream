@extends('app')

@section('title', trans('title.reporting_organisation'))

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                <div class="panel-content-heading">
                    <div>@lang('element.reporting_organisation')
                        <div class="panel-action-btn">
                            <a href="{{route('organization.show', $organizationId)}}" class="btn btn-primary">@lang('global.view_organisation_data')
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
                                        <div class="col-xs-12 col-sm-4 col-lg-3">@lang('elementForm.identifier'):</div>
                                        <div class="col-xs-12 col-sm-8 col-lg-9">{{ $reportingOrganization[0]['reporting_organization_identifier'] }}</div>
                                    </div>
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-12 col-sm-4 col-lg-3">@lang('elementForm.type'):</div>
                                        <div class="col-xs-12 col-sm-8 col-lg-9">{{ $reportingOrganization[0]['reporting_organization_type'] }}</div>
                                    </div>
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-12 col-sm-4 col-lg-3">@lang('elementForm.name'):</div>
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
                            <br/>
                        </div>
                        <div class="activity-description"><span>@lang('global.reporting_organisation_update') <a href="{{ route('settings') }}">@lang('global.settings')</a>.</span></div>
                    </div>
                </div>
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
