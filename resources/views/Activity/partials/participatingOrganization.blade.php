@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['participating_organization'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.participating_organisation') @if(array_key_exists('Participating Organization',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupActivityElements(getVal($activityDataList, ['participating_organization'], []) , 'organization_role') as $key => $organizations)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">
                    {{ $getCode->getCodeNameOnly('OrganisationRole', $key)}} Organisation(s)
                </div>
                <div class="activity-element-info">
                    @foreach($organizations as $organization)
                        <li>
                            {!!  getFirstNarrative($organization)  !!}
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($organization, ['narrative'], []))])
                        </li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.identifier')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getVal($organization, ['identifier'], [])) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.organisation_type')</div>
                                <div class="activity-element-info">
                                    @if(!empty(getVal($organization, ['organization_type'])))
                                        {{getVal($organization, ['organization_type']) . ' - ' . $getCode->getCodeNameOnly("OrganisationType",getVal($organization, ['organization_type'])) }}
                                    @else
                                        <em>Not Available</em>
                                    @endif
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.organisation_role')</div>
                                <div class="activity-element-info">{{getVal($organization, ['organization_role']) . ' - ' . $getCode->getCodeNameOnly("OrganisationRole",getVal($organization, ['organization_role'])) }}</div>
                            </div>
                            @if(session('version') != 'V201')
                                @if(array_key_exists('activity_id' , $organization))
                                    <div class="element-info">
                                        <div class="activity-element-label">@lang('elementForm.activity_id')</div>
                                        <div class="activity-element-info">{!! checkIfEmpty($organization['activity_id']) !!}</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.participating-organization.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'participating_organization', 'id' => $id])
    </div>
@endif
