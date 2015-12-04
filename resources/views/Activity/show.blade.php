@extends('app')

@section('content')

    {{Session::get('message')}}

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
                                                        <div class="col-xs-8">{{$other_identifier['type']}}</div>
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
                                                            <div class="col-xs-8">{{$narrative['narrative'] . '-'. $narrative['language']}}</div>
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
                                <div class="panel-body">
                                @foreach($titles as $title)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                                        <div class="panel-body panel-element-body row">
                                            <div class="form-group clearfix">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Narrative Text</div>
                                                    <div class="col-xs-8">{{$title['narrative'] . ' [' . $title['language'] . ']'}}</div>
                                                </div>
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
                                <div class="panel-body">
                                    @foreach($descriptions as $description)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$description['type']}}</div>
                                            <div class="panel-body panel-element-body row">
                                                <div class="form-group clearfix">
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Type</div>
                                                        <div class="col-xs-8">{{$description['type']}}</div>
                                                    </div>
                                                    @foreach($description['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Narrative Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
                                                    </div>
                                                    @endforeach
                                                </div>
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
                                    <div class="col-xs-8">{{ $activityStatus[0] }}</div>
                                </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($activityDates))
                            <div class="panel panel-default">
                                <div class="panel-heading">Activity Date</div>
                                <div class="panel-body">
                                    @foreach($activityDates as $activity_date)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$activity_date['type'] . ';' . $activity_date['date']}}</div>
                                            <div class="panel-body panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Type</div>
                                                <div class="col-xs-8">{{$activity_date['type']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Date</div>
                                                <div class="col-xs-8">{{$activity_date['date']}}</div>
                                            </div>
                                                @foreach($activity_date['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Text</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
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
                                            <div class="col-xs-8">{{ $info['type'] }}</div>
                                        </div>
                                            @foreach($info['organization'] as $organization)
                                                @foreach($organization['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Organization Name</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
                                                </div>
                                                @endforeach
                                            @endforeach
                                            @foreach($info['department'] as $department)
                                                @foreach($department['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Department</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
                                                </div>
                                                @endforeach
                                            @endforeach
                                            @foreach($info['person_name'] as $person_name)
                                                @foreach($person_name['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Person Name</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
                                                </div>
                                                @endforeach
                                            @endforeach
                                            @foreach($info['job_title'] as $job_title)
                                                @foreach($job_title['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Job Title</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
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
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' [' . $narrative['language'] . ']'}}</div>
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
                                                <div class="col-xs-8">{{ $activityScope }}</div>
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
                                            <div class="panel-heading">{{$participatingOrganization['organization_role']}}</div>
                                            <div class="panel-body panel-element-body row">
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Ref</div>
                                                <div class="col-xs-8">{{$participatingOrganization['identifier']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Type</div>
                                                <div class="col-xs-8">{{$participatingOrganization['organization_type']}}</div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-xs-4">Role</div>
                                                <div class="col-xs-8">{{$participatingOrganization['organization_role']}}</div>
                                            </div>
                                                @foreach($participatingOrganization['narrative'] as $narrative)
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Organization Name</div>
                                                    <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $narrative['language'] . ']'}}</div>
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
                                                <div class="panel-heading">{{$recipientCountry['country_code'] . ';' . $recipientCountry['percentage']}}</div>
                                                <div class="panel-body panel-element-body row">
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Percentage</div>
                                                    <div class="col-xs-8">{{$recipientCountry['percentage']}}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-xs-4">Code</div>
                                                    <div class="col-xs-8">{{$recipientCountry['country_code']}}</div>
                                                </div>
                                                    @foreach($recipientCountry['narrative'] as $narrative)
                                                    <div class="col-md-12">
                                                        <div class="col-xs-4">Text</div>
                                                        <div class="col-xs-8">{{$narrative['narrative'] . ' ['. $narrative['language'] . ']'}}</div>
                                                    </div>
                                                    @endforeach
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
