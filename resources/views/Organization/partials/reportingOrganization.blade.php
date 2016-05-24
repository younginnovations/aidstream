@if(!emptyOrHasEmptyTemplate($reporting_org))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.reporting_organization')</div>
            <div class="activity-element-info">
                {!! checkIfEmpty(getFirstNarrative($reporting_org)) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($reporting_org['narrative'])])
                <div class="toggle-btn">
                    <span class="show-more-info">Show more info</span>
                    <span class="hide-more-info hidden">Hide more info</span>                 
                </div>
                <div class="more-info hidden">
                    <div class="element-info">
                        <div class="activity-element-label pull-left">@lang('activityView.identifier')</div>
                        <div class="activity-element-info pull-left">{!! checkIfEmpty($reporting_org['reporting_organization_identifier']) !!}</div>
                    </div>
                    <div class="element-info">
                        <div class="activity-element-label pull-left">@lang('activityView.organization_type')</div>
                        <div class="activity-element-info pull-left">{!! getCodeNameWithCodeValue('OrganisationType' , $reporting_org['reporting_organization_type'] , -4) !!}</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="edit-element">edit</a>
    </div>
@endif
