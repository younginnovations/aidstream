@extends('app')
@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
<div class="container main-container">
   <div class="row">
       @include('includes.side_bar_menu')
       <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
           @include('includes.breadcrumb')
           <?php
           $activity_workflow = $activityDataList['activity_workflow'];
           $status_label = ['draft', 'completed', 'verified', 'published'];
           $btn_status_label = ['Complete', 'Verify', 'Publish'];
           $btn_text = $activity_workflow > 2 ? "" : $btn_status_label[$activity_workflow];
           ?>
           <div class="element-panel-heading">
               <span>Activity Data</span>
                 @if($btn_text != "")
                       <form method="POST" id="change_status" class="pull-right" action="{{ url('/activity/' . $id . '/update-status') }}">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                           <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                           @if($activity_workflow == 2)
                               <input type="button" value="{{ $btn_text }}" class="btn_confirm"
                                      data-title="Confirmation" data-message="Are you sure you want to Publish?">
                           @else
                               <input type="submit" value="{{ $btn_text }}">
                           @endif
                       </form>
                   @endif
           </div>
           <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
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
               </div>

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
                       $policyMakers = $activityDataList['policy_maker'];
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
                        
                        @if(!empty($identifier))
                            <div class="panel panel-default">
                                <div class="panel-heading">Identifier</div>
                                <div class="panel-body panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">IATI Identifier Text: </div>
                                        <div class="col-xs-8">{{ $identifier['iati_identifier_text'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($otherIdentifiers))
                            <div class="panel panel-default">
                                <div class="panel-heading">Other Identifier</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($otherIdentifiers as $other_identifier)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{$other_identifier['reference']}}</div>
                                        <div class="panel-body row">
                                            <div class="panel-element-body">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Reference</div>
                                                    <div class="col-xs-8">{{$other_identifier['reference']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Type</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('OtherIdentifierType', $other_identifier['type'])}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12 panel-level-2">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Owner Org</div>
                                                    <div class="panel-body panel-element-body row">
                                                        @foreach($other_identifier['owner_org'] as $owner_org)
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Owner Org</div>
                                                            <div class="col-xs-8">{{$owner_org['reference']}}</div>
                                                        </div>
                                                        @foreach($owner_org['narrative'] as $narrative)
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Text</div>
                                                            <div class="col-xs-8">{{$narrative['narrative'] . '-'. $getCode->getOrganizationCodeName('Language', $narrative['language'])}}</div>
                                                        </div>
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($titles))
                            <div class="panel panel-default">
                                <div class="panel-heading">Title</div>
                                <div class="panel-body panel-level-1">
                                @foreach($titles as $title)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                                        <div class="panel-body panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Narrative Text</div>
                                                <div class="col-xs-8">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($descriptions))
                            <div class="panel panel-default">
                                <div class="panel-heading">Description</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($descriptions as $description)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('DescriptionType', $description['type'])}}</div>
                                            <div class="panel-body panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Type</div>
                                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('DescriptionType', $description['type'])}}</div>
                                                    </div>
                                                    @foreach($description['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Narrative Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($activityStatus))
                            <div class="panel panel-default">
                                <div class="panel-heading">Activity Status</div>
                                <div class="panel-body panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('ActivityStatus', $activityStatus[0])}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($activityDates))
                            <div class="panel panel-default">
                                <div class="panel-heading">Activity Date</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($activityDates as $activity_date)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type']) . ';' . $activity_date['date']}}</div>
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Type</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('ActivityDateType', $activity_date['type'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Date</div>
                                                    <div class="col-xs-8">{{$activity_date['date']}}</div>
                                                </div>
                                                @foreach($activity_date['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Text</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($contactInfo))
                            <div class="panel panel-default">
                                <div class="panel-heading">Contact Info</div>
                                <div class="panel-body panel-element-body">
                                    @foreach($contactInfo as $info)
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Type</div>
                                        <div class="col-xs-8">{{ $getCode->getActivityCodeName('ContactType', $info['type']) }}</div>
                                    </div>
                                        @foreach($info['organization'] as $organization)
                                            @foreach($organization['narrative'] as $narrative)
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Organization Name</div>
                                                <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                            @endforeach
                                        @endforeach
                                        @foreach($info['department'] as $department)
                                            @foreach($department['narrative'] as $narrative)
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Department</div>
                                                <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                            @endforeach
                                        @endforeach
                                        @foreach($info['person_name'] as $person_name)
                                            @foreach($person_name['narrative'] as $narrative)
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Person Name</div>
                                                <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                            @endforeach
                                        @endforeach
                                        @foreach($info['job_title'] as $job_title)
                                            @foreach($job_title['narrative'] as $narrative)
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Job Title</div>
                                                <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                            </div>
                                            @endforeach
                                        @endforeach
                                        @foreach($info['telephone'] as $telephone)
                                            <div class="col-md-12 clearfix">
                                                <div class="col-xs-4">Telephone</div>
                                                <div class="col-xs-8">{{$telephone['telephone']}}</div>
                                            </div>
                                        @endforeach
                                        @foreach($info['email'] as $email)
                                            <div class="col-md-12 clearfix">
                                                <div class="col-xs-4">Email</div>
                                                <div class="col-xs-8">{{$email['email']}}</div>
                                            </div>
                                        @endforeach
                                        @foreach($info['website'] as $website)
                                            <div class="col-md-12 clearfix">
                                                <div class="col-xs-4">Website</div>
                                                <div class="col-xs-8">{{$website['website']}}</div>
                                            </div>
                                        @endforeach
                                        @foreach($info['mailing_address'] as $mailing_address)
                                            @foreach($mailing_address['narrative'] as $narrative)
                                                <div class="col-md-12 clearfix">
                                                    <div class="col-xs-4">Job Title</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $getCode->getOrganizationCodeName('Language', $getCode->getOrganizationCodeName('Language', $narrative['language'])) . ']'}}</div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($activityScope))
                            <div class="panel panel-default">
                                <div class="panel-heading">Activity Scope</div>
                                <div class="panel-body panel-element-body">
                                    <div class="col-md-12 clearfix">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{ $getCode->getActivityCodeName('ContactType', $activityScope) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($participatingOrganizations))
                            <div class="panel panel-default">
                                <div class="panel-heading">Participating Organization</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($participatingOrganizations as $participatingOrganization)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{ $getCode->getActivityCodeName('OrganisationRole', $participatingOrganization['organization_role']) }}</div>
                                            <div class="panel-body panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Ref</div>
                                                <div class="col-xs-8">{{$participatingOrganization['identifier']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Type</div>
                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('OrganisationType', $participatingOrganization['organization_type'])}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Role</div>
                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('OrganisationRole', $participatingOrganization['organization_role'])}}</div>
                                            </div>
                                                @foreach($participatingOrganization['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Organization Name</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $getCode->getOrganizationCodeName('Language', $narrative['language'])) . ']'}}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($recipientCountries))
                            <div class="panel panel-default">
                                <div class="panel-heading">Recipient Country</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($recipientCountries as $recipientCountry)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getOrganizationCodeName('Country', $recipientCountry['country_code']) . ' ; ' . $recipientCountry['percentage']}}</div>
                                            <div class="panel-body panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Percentage</div>
                                                <div class="col-xs-8">{{$recipientCountry['percentage']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Code</div>
                                                <div class="col-xs-8">{{$getCode->getOrganizationCodeName('Country', $recipientCountry['country_code'])}}</div>
                                            </div>
                                                @foreach($recipientCountry['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Text</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $getCode->getOrganizationCodeName('Language', $narrative['language'])) . ']'}}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($recipientRegions))
                            <div class="panel panel-default">
                                <div class="panel-heading">Recipient Region</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($recipientRegions as $recipientRegion)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('Region', $recipientRegion['region_code']) . ' ; ' . $recipientRegion['percentage']}}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Percentage</div>
                                                    <div class="col-xs-8">{{$recipientRegion['percentage']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('Region', $recipientRegion['region_code'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Vocabulary</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('RegionVocabulary', $recipientRegion['region_vocabulary'])}}</div>
                                                </div>
                                                 @foreach($recipientRegion['narrative'] as $narrative)
                                                 <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($locations))
                            <div class="panel panel-default">
                                <div class="panel-heading">Location</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($locations as $location)
                                        <div class="panel panel-default">
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Reference</div>
                                                    <div class="col-xs-8">{{$location['reference']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Location Reach</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicLocationReach', $location['location_reach'][0]['code'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Location Id</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicLocationReach', $location['location_reach'][0]['code'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Vocabulary</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicVocabulary', $location['location_id'][0]['vocabulary'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Name</div>
                                            <div class="panel-element-body row">
                                                    @foreach($location['name'][0]['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Description</div>
                                            <div class="panel-element-body row">
                                                    @foreach($location['location_description'][0]['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Activity Description</div>
                                            <div class="panel-element-body row">
                                                    @foreach($location['activity_description'][0]['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Administrative</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$location['administrative'][0]['code']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Administrative</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicVocabulary', $location['administrative'][0]['vocabulary'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Level</div>
                                                    <div class="col-xs-8">{{$location['administrative'][0]['level']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Point</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Srs Name</div>
                                                    <div class="col-xs-8">{{$location['point'][0]['srs_name']}}</div>
                                                </div>
                                                    @foreach($location['point'][0]['position'] as $position)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$position['latitude'] . ' , '. $position['longitude']}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Exactness</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicExactness',$location['exactness'][0]['code'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Location Class</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('GeographicLocationClass',$location['location_class'][0]['code'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Feature Designation</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('LocationType', $location['feature_designation'][0]['code'])}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($sectors))
                            <div class="panel panel-default">
                                <div class="panel-heading">Sectors</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($sectors as $sector)
                                        {{--*/
                                            $vocabulary = $sector['sector_vocabulary'];
                                            $vocabularyValue = $getCode->getActivityCodeName('SectorVocabulary', $vocabulary);
                                            if ($vocabulary == 1) {
                                                $sectorValue = $getCode->getActivityCodeName('Sector', $sector['sector_code']);
                                            } elseif ($vocabulary == 2) {
                                                $sectorValue = $getCode->getActivityCodeName('SectorCategory', $sector['sector_category_code']);
                                            } else {
                                                $sectorValue = $sector['sector_text'];
                                            }
                                        /*--}}
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{ $vocabularyValue . ' ; ' . $sectorValue }}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Vocabulary</div>
                                                    <div class="col-xs-8">{{ $vocabularyValue }}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{ $sectorValue }}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Percentage</div>
                                                    <div class="col-xs-8">{{$sector['percentage']}}</div>
                                                </div>
                                                    @foreach($sector['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Text</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($countryBudgetItems))
                            <div class="panel panel-default">
                                <div class="panel-heading">Country Budget Items</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($countryBudgetItems as $countryBudgetItem)
                                        <div class="panel panel-default">
                                        <div class="panel-element-body">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Vocabulary</div>
                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('BudgetIdentifierVocabulary', $countryBudgetItem['vocabulary'])}}</div>
                                            </div>
                                        </div>
                                            <div class="panel-heading">Budget Item</div>
                                            <div class="panel-element-body">
                                            @foreach($countryBudgetItem['budget_item'] as $budgetItem)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('BudgetIdentifier', $budgetItem['code'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Percentage</div>
                                                    <div class="col-xs-8">{{$budgetItem['percentage']}}</div>
                                                </div>
                                                    @foreach($budgetItem['description'] as $budgetNarrative)
                                                        @foreach($budgetNarrative['narrative'] as $narrative)
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Text</div>
                                                            <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                        </div>
                                                        @endforeach
                                                    @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($policyMakers))
                            <div class="panel panel-default">
                                <div class="panel-heading">Policy Makers</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($policyMakers as $policyMaker)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('PolicyMarker', $policyMaker['policy_marker'])}}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Significance</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('PolicySignificance', $policyMaker['significance'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Policy Maker</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('PolicyMarker', $policyMaker['policy_marker'])}}</div>
                                                </div>
                                                    @foreach($policyMaker['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($collaborationType))
                            <div class="panel panel-default">
                                <div class="panel-heading">Collaboration Type</div>
                                <div class="panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('CollaborationType', $collaborationType)}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($defaultFlowType))
                            <div class="panel panel-default">
                                <div class="panel-heading">Default FLow Type</div>
                                <div class="panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('FlowType', $defaultFlowType)}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($defaultFinanceType))
                            <div class="panel panel-default">
                                <div class="panel-heading">Default Finance Type</div>
                                <div class="panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('FinanceType', $defaultFinanceType)}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($defaultAidType))
                            <div class="panel panel-default">
                                <div class="panel-heading">Default Aid Type</div>
                                <div class="panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('AidType', $defaultAidType)}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($defaultTiedStatus))
                            <div class="panel panel-default">
                                <div class="panel-heading">Default Tied Status</div>
                                <div class="panel-element-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Code</div>
                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus)}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($budgets))
                            <div class="panel panel-default">
                                <div class="panel-heading">Budgets</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($budgets as $budget)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('BudgetType', $budget['budget_type']) . ' ; [USD] '. $budget['value'][0]['amount'] . ' ; '. $budget['value'][0]['value_date'] }}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Type</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('BudgetType', $budget['budget_type'])}}</div>
                                                </div>

                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period Start</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Iso_date</div>
                                                            <div class="col-xs-8">{{$budget['period_start'][0]['date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period End</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Iso_date</div>
                                                            <div class="col-xs-8">{{$budget['period_end'][0]['date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Value</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Amount</div>
                                                            <div class="col-xs-8">{{$budget['value'][0]['amount']}}</div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Currency</div>
                                                            <div class="col-xs-8">{{$getCode->getActivityCodeName('Currency', $budget['value'][0]['currency'])}}</div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Date</div>
                                                            <div class="col-xs-8">{{$budget['value'][0]['value_date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($plannedDisbursements))
                            <div class="panel panel-default">
                                <div class="panel-heading">Planned Disbursements</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($plannedDisbursements as $plannedDisbursement)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{'[USD]'. $plannedDisbursement['value'][0]['amount'] . ' ; '. $plannedDisbursement['value'][0]['value_date'] }}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Type</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('BudgetType', $plannedDisbursement['planned_disbursement_type'])}}</div>
                                                </div>

                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period Start</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Iso_date</div>
                                                            <div class="col-xs-8">{{$plannedDisbursement['period_start'][0]['date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Period End</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Iso_date</div>
                                                            <div class="col-xs-8">{{$plannedDisbursement['period_end'][0]['date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Value</div>
                                                    <div class="panel-element-body row">
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Amount</div>
                                                            <div class="col-xs-8">{{$plannedDisbursement['value'][0]['amount']}}</div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Currency</div>
                                                            <div class="col-xs-8">{{$getCode->getActivityCodeName('Currency', $plannedDisbursement['value'][0]['currency'])}}</div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Date</div>
                                                            <div class="col-xs-8">{{$plannedDisbursement['value'][0]['value_date']}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($capitalSpend))
                            <div class="panel panel-default">
                                <div class="panel-heading">Capital Spend</div>
                                <div class="panel-body row">
                                    <div class="col-md-12">
                                        <div class="col-xs-4">Percentage</div>
                                        <div class="col-xs-8">{{$capitalSpend}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($documentLinks))
                            <div class="panel panel-default">
                                <div class="panel-heading">Document Link</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($documentLinks as $documentLink)
                                        <div class="panel panel-default">
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Url </div>
                                                    <div class="col-xs-8">{{$documentLink['url']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Format </div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('FileFormat', $documentLink['format'])}}</div>
                                                </div>

                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Title</div>
                                                    @foreach($documentLink['title'] as $title)
                                                        @foreach($title['narrative'] as $narrative)
                                                            <div class="panel-element-body row">
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Iso_date</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Category</div>
                                                    <div class="panel-element-body row">
                                                        @foreach($documentLink['category'] as $category)
                                                            <div class="col-md-12">
                                                                <div class="col-xs-4">Code</div>
                                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('DocumentCategory', $category['code'])}}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">Language</div>
                                                    <div class="panel-element-body row">
                                                        @foreach($documentLink['language'] as $language)
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Code</div>
                                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('Language', $language['language'])}}</div>
                                                                </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($relatedActivities))
                            <div class="panel panel-default">
                                <div class="panel-heading">Related Activity</div>
                                <div class="panel-body panel-level-1">
                                @foreach($relatedActivities as $relatedActivity)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{$relatedActivity['activity_identifier']}}</div>
                                        <div class="panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Ref</div>
                                                <div class="col-xs-8">{{$relatedActivity['activity_identifier']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Type</div>
                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('RelatedActivityType', $relatedActivity['relationship_type'])}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($legacyDatas))
                            <div class="panel panel-default">
                                <div class="panel-heading">Legacy Data</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($legacyDatas as $legacyData)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$legacyData['name'] . ';' . $legacyData['value']}}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Name</div>
                                                    <div class="col-xs-8">{{$legacyData['name']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Value</div>
                                                    <div class="col-xs-8">{{$legacyData['value']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Iati Equivalent</div>
                                                    <div class="col-xs-8">{{$legacyData['iati_equivalent']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($conditions))
                            <div class="panel panel-default">
                                <div class="panel-heading">Conditions</div>
                                <div class="panel-body panel-level-1">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Attached : {{($conditions['condition_attached'] == "1") ? 'Yes' : 'No' }}</div>
                                    </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading">Description</div>
                                            <div class="panel-element--body row">
                                                    @foreach($conditions['condition'] as $data)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Type</div>
                                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('ConditionType', $data['condition_type'])}}</div>
                                                    </div>
                                                        @foreach($data['narrative'] as $narrative)
                                                            <div class="panel-element-body row">
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Text</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($results))
                            <div class="panel panel-default">
                                <div class="panel-heading">Results</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($results as $result)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Type</div>
                                                    <div class="col-xs-8">{{$getCode->getActivityCodeName('ResultType', $result['result']['type'])}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Aggregation Status</div>
                                                    <div class="col-xs-8">{{($result['result']['aggregation_status'] == "1") ? 'True' : 'False' }}</div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Title</div>
                                                <div class="panel-body row">
                                                    @foreach($result['result']['title'] as $title)
                                                        @foreach($title['narrative'] as $narrative)
                                                            <div class="panel-element-body row">
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Text</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Description</div>
                                                <div class="panel-body row">
                                                    @foreach($result['result']['description'] as $description)
                                                        @foreach($description['narrative'] as $narrative)
                                                            <div class="panel-body row">
                                                                <div class="form-group clearfix">
                                                                    <div class="col-xs-4">Text</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Indicator</div>
                                                <div class="panel-body row">
                                                    @foreach($result['result']['indicator'] as $indicator)
                                                        <div class="panel-body row">
                                                            <div class="form-group clearfix">
                                                                <div class="col-xs-4">Measure</div>
                                                                <div class="col-xs-8">{{$getCode->getActivityCodeName('IndicatorMeasure', $indicator['measure'])}}</div>
                                                                <div class="col-xs-4">Ascending</div>
                                                                <div class="col-xs-8">{{($indicator['ascending'] == "1") ? 'True' : 'False' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">Title</div>
                                                            <div class="panel-body row">
                                                                @foreach($indicator['title'] as $title)
                                                                    @foreach($title['narrative'] as $narrative)
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Text</div>
                                                                                <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">Description</div>
                                                            <div class="panel-body row">
                                                                @foreach($indicator['description'] as $description)
                                                                    @foreach($description['narrative'] as $narrative)
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Text</div>
                                                                                <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">Baseline</div>
                                                            <div class="panel-body row">
                                                                @foreach($indicator['baseline'] as $baseline)
                                                                    <div class="panel-body row">
                                                                        <div class="form-group clearfix">
                                                                            <div class="col-xs-4">Year</div>
                                                                            <div class="col-xs-8">{{$baseline['year']}}</div>
                                                                            <div class="col-xs-4">Value</div>
                                                                            <div class="col-xs-8">{{$baseline['value']}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">Comment</div>
                                                                        <div class="panel-body row">
                                                                        @foreach($baseline['comment'] as $comment)
                                                                            @foreach($comment['narrative'] as $narrative)
                                                                                <div class="panel-body row">
                                                                                    <div class="form-group clearfix">
                                                                                        <div class="col-xs-4">Text</div>
                                                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">Period</div>
                                                            <div class="panel-body row">
                                                                @foreach($indicator['period'] as $period)
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">Period Start</div>
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Iso_date</div>
                                                                                <div class="col-xs-8">{{$period['period_start'][0]['date']}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">Period End</div>
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Iso_date</div>
                                                                                <div class="col-xs-8">{{$period['period_end'][0]['date']}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">Target</div>
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Iso_date</div>
                                                                                <div class="col-xs-8">{{$period['target'][0]['value']}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-heading">Comment</div>
                                                                        <div class="panel-body row">
                                                                            @foreach($period['target'][0]['comment'] as $comment)
                                                                                @foreach($comment['narrative'] as $narrative)
                                                                                    <div class="panel-body row">
                                                                                        <div class="form-group clearfix">
                                                                                            <div class="col-xs-4">Text</div>
                                                                                            <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">Actual</div>
                                                                        <div class="panel-body row">
                                                                            <div class="form-group clearfix">
                                                                                <div class="col-xs-4">Iso_date</div>
                                                                                <div class="col-xs-8">{{$period['actual'][0]['value']}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-heading">Comment</div>
                                                                        <div class="panel-body row">
                                                                            @foreach($period['actual'][0]['comment'] as $comment)
                                                                                @foreach($comment['narrative'] as $narrative)
                                                                                    <div class="panel-body row">
                                                                                        <div class="form-group clearfix">
                                                                                            <div class="col-xs-4">Text</div>
                                                                                            <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endforeach
                                                                        </div>
                                                                    </div>

                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($transactions))
                            <div class="panel panel-default">
                                <div class="panel-heading">Transactions</div>
                                <div class="panel-body panel-level-1">
                                    @foreach($transactions as $transaction)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                {{$getCode->getActivityCodeName('TransactionType', $transaction['transaction']['transaction_type'][0]['transaction_type_code']) .' ; '. $transaction['transaction']['value'][0]['amount'] . ' ; ' . $transaction['transaction']['value'][0]['date']}}
                                            </div>
                                            <div class="panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Ref</div>
                                                    <div class="col-xs-8">{{$transaction['transaction']['reference']}}</div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Transaction Type</div>
                                                <div class="panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Code</div>
                                                        <div class="col-xs-8">{{$getCode->getActivityCodeName('TransactionType', $transaction['transaction']['transaction_type'][0]['transaction_type_code'])}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Transaction Date</div>
                                                <div class="panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Date</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['transaction_date'][0]['date']}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Value</div>
                                                <div class="panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Amount</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['value'][0]['amount']}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Date</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['value'][0]['date']}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Currency</div>
                                                        <div class="col-xs-8">{{$getCode->getOrganizationCodeName('Currency', $transaction['transaction']['value'][0]['currency'])}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Description</div>
                                                <div class="panel-element-body row">
                                                    @foreach($transaction['transaction']['description'] as $description)
                                                        @foreach($description['narrative'] as $narrative)
                                                            <div class="panel-body row">
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Text</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Provider Organization</div>
                                                <div class="panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Ref</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['provider_organization'][0]['organization_identifier_code']}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Provider_activity_id</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['provider_organization'][0]['provider_activity_id']}}</div>
                                                    </div>
                                                        @foreach($transaction['transaction']['provider_organization'] as $narrative)
                                                        @foreach($narrative['narrative'] as $narrative)
                                                                <div class="panel-body row">
                                                                    <div class="col-md-12">
                                                                        <div class="col-xs-4">Text</div>
                                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                </div>
                                            </div>                                                
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Receiver Organization</div>
                                                <div class="panel-element-body row">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Ref</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['receiver_organization'][0]['organization_identifier_code']}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Provider_activity_id</div>
                                                        <div class="col-xs-8">{{$transaction['transaction']['receiver_organization'][0]['receiver_activity_id']}}</div>
                                                    </div>
                                                        @foreach($transaction['transaction']['receiver_organization'] as $narrative)
                                                            @foreach($narrative['narrative'] as $narrative)
                                                                <div class="panel-body row">
                                                                    <div class="col-md-12">
                                                                        <div class="col-xs-4">Text</div>
                                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                            </div>
                                             <div class="panel panel-default">
                                                <div class="panel-heading">Sector</div>
                                                <div class="panel-element-body row">
                                                        {{--*/
                                                            $vocabulary = $sector['sector_vocabulary'];
                                                            $vocabularyValue = $getCode->getActivityCodeName('SectorVocabulary', $vocabulary);
                                                            if ($vocabulary == 1) {
                                                                $sectorValue = $getCode->getActivityCodeName('Sector', $sector['sector_code']);
                                                            } elseif ($vocabulary == 2) {
                                                                $sectorValue = $getCode->getActivityCodeName('SectorCategory', $sector['sector_category_code']);
                                                            } else {
                                                                $sectorValue = $sector['sector_text'];
                                                            }
                                                        /*--}}
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Vocabulary</div>
                                                            <div class="col-xs-8">{{ $vocabularyValue }}</div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="col-xs-4">Code</div>
                                                            <div class="col-xs-8">{{ $sectorValue }}</div>
                                                        </div>
                                                        @foreach($transaction['transaction']['sector'] as $narrative)
                                                            @foreach($narrative['narrative'] as $narrative)
                                                                <div class="col-md-12">
                                                                    <div class="col-xs-4">Text</div>
                                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $getCode->getOrganizationCodeName('Language', $narrative['language']) . ']'}}</div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
        </div>
    </div>
</div>
@endsection
