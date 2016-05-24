@if(!emptyOrHasEmptyTemplate($participatingOrganizations))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.participating_organization')</div>
        @foreach(groupActivityElements($participatingOrganizations , 'organization_role') as $key => $organizations)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('OrganisationRole', $key)}} Organization(s)
                </div>
                <div class="activity-element-info">
                    @foreach($organizations as $organization)
                        <li>
                            {!!  getFirstNarrative($organization)  !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($organization['narrative'])])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.identifier')</div>
                                <div class="activity-element-info">{!! checkIfEmpty($organization['identifier']) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.organization_type')</div>
                                <div class="activity-element-info">
                                    @if(!empty($organization['organization_type']))
                                        {{$organization['organization_type'] . ' - ' . $getCode->getCodeNameOnly("OrganisationType",$organization['organization_type']) }}
                                    @else
                                        <em>Not Available</em>
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.organization_role')</div>
                                <div class="activity-element-info">{{$organization['organization_role'] . ' - ' . $getCode->getCodeNameOnly("OrganisationRole",$organization['organization_role']) }}</div>
                            </div>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('activity_id' , $organization))
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('activityView.activity_id')</div>
                                        <div class="activity-element-info">{!! checkIfEmpty($organization['activity_id']) !!}</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.participating-organization.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'participating_organization'])}}" class="delete pull-right">remove</a>
    </div>
@endif
