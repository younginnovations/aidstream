@extends('app')

@section('title', 'Activity Data')

@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <?php
                $activity_workflow = $activityDataList['activity_workflow'];
                $status_label = ['draft', 'completed', 'verified', 'published'];
                $btn_status_label = ['Completed', 'Verified', 'Published'];
                $btn_text = $activity_workflow > 2 ? "" : $btn_status_label[$activity_workflow];
                ?>
                <div class="element-panel-heading">
                    <span>{{ $activityDataList['title'] ? $activityDataList['title'][0]['narrative'] : 'No Title' }}</span>
                    <div class="element-panel-heading-info">
                        <span>US-EIN-042347643-200708080/PO4048</span>
                        <span class="last-updated-date">Last Updated on: Oct 4, 2015 3h 24m 42s</span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="activity-status activity-status-{{ $status_label[$activity_workflow] }}">
                        <ol>
                            @foreach($status_label as $key => $val)
                                @if($key == $activity_workflow)
                                    <li class="active"><span>{{ $val }}</span></li>
                                @else
                                    <li><span>{{ $val }}</span></li>
                                @endif
                            @endforeach
                        </ol>
                        @if($btn_text != "")
                            <form method="POST" id="change_status" class="pull-right" action="{{ url('/activity/' . $id . '/update-status') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                                @if($activity_workflow == 2)
                                    <input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
                                           data-title="Confirmation" data-message="Are you sure you want to Publish?">
                                @else
                                    <input type="submit" value="Mark as {{ $btn_text }}">
                                @endif
                            </form>
                        @endif
                    </div>
                    <a href="{{route('change-activity-default', $id)}}" class="pull-right"><span class="glyphicon glyphicon-triangle-left"></span>Override Activity Default</a>
                    <div class="panel panel-default panel-element-detail">
                        <div class="panel-body">
                            {{--*/
                            $identifier = $activityDataList['identifier'];
                            $otherIdentifiers = $activityDataList['other_identifier'];
                            $titles = $activityDataList['title'];
                            $descriptions = $activityDataList['description'];
                            $activityStatus = $activityDataList['activity_status'];
                            $activityDates = $activityDataList['activity_date'];
                            $activityScope = $activityDataList['activity_scope'];
                            $contactInfo = $activityDataList['contact_info'];
                            $participatingOrganizations = $activityDataList['participating_organization'];
                            $recipientCountries = $activityDataList['recipient_country'];
                            $recipientRegions = $activityDataList['recipient_region'];
                            $locations = $activityDataList['location'];
                            $sectors = $activityDataList['sector'];
                            $countryBudgetItems = $activityDataList['country_budget_items'];
                            $policyMarkers = $activityDataList['policy_marker'];
                            $collaborationType = $activityDataList['collaboration_type'];
                            $defaultFlowType = $activityDataList['default_flow_type'];
                            $defaultFinanceType = $activityDataList['default_finance_type'];
                            $defaultAidType = $activityDataList['default_aid_type'];
                            $defaultTiedStatus = $activityDataList['default_tied_status'];
                            $budgets = $activityDataList['budget'];
                            $plannedDisbursements = $activityDataList['planned_disbursement'];
                            $documentLinks = $activityDataList['document_link'];
                            $relatedActivities = $activityDataList['related_activity'];
                            $legacyDatas = $activityDataList['legacy_data'];
                            $conditions = $activityDataList['conditions'];
                            $results = $activityDataList['results'];
                            $transactions = $activityDataList['transaction'];
                            /*--}}

                            @include('Activity.partials.identifier')
                            @include('Activity.partials.otherIdentifier')
                            @include('Activity.partials.title')
                            @include('Activity.partials.description')
                            @include('Activity.partials.activityStatus')
                            @include('Activity.partials.activityDate')
                            @include('Activity.partials.contactInfo')
                            @include('Activity.partials.activityScope')
                            @include('Activity.partials.participatingOrganization')
                            @include('Activity.partials.recipientCountry')
                            @include('Activity.partials.recipientRegion')
                            @include('Activity.partials.location')
                            @include('Activity.partials.sector')
                            @include('Activity.partials.countryBudgetItem')
                            @include('Activity.partials.policyMarker')
                            @include('Activity.partials.collaborationType')
                            @include('Activity.partials.defaultFlowType')
                            @include('Activity.partials.defaultFinanceType')
                            @include('Activity.partials.defaultAidType')
                            @include('Activity.partials.defaultTiedStatus')
                            @include('Activity.partials.budget')
                            @include('Activity.partials.plannedDisbursement')
                            @include('Activity.partials.capitalSpend')
                            @include('Activity.partials.documentLink')
                            @include('Activity.partials.relatedActivity')
                            @include('Activity.partials.legacyData')
                            @include('Activity.partials.condition')
                            @include('Activity.partials.result')
                            @include('Activity.partials.transaction')
                        </div>
                    </div>
                </div>
                @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection
