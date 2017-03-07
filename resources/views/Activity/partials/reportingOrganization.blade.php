@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['reporting_org', 0], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.reporting_organisation')</div>
            <div class="activity-element-info">
                <li>{!! checkIfEmpty(getFirstNarrative(getVal($activityDataList, ['reporting_org', 0], []))) !!}
                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages(getVal($activityDataList, ['reporting_org', 0, 'narrative'], []))])
                </li>
                <div class="toggle-btn">
                    <span class="show-more-info">@lang('global.show_more_info')</span>
                    <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                </div>

                <div class="more-info hidden">
                    <div class="element-info">
                        <div class="activity-element-label">@lang('elementForm.organisation_identifier')</div>
                        <div class="activity-element-info">{!! checkIfEmpty(getVal($activityDataList, ['reporting_org', 0, 'reporting_organization_identifier'], [])) !!}</div>
                    </div>
                    <div class="element-info">
                        <div class="activity-element-label">@lang('elementForm.organisation_type')</div>
                        <div class="activity-element-info">{!! substr($getCode->getOrganizationCodeName('OrganizationType', getVal($activityDataList, ['reporting_org', 0, 'reporting_organization_type'])), 0, -4) !!}</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('settings') }}" class="edit-element"></a>
    </div>
@endif
