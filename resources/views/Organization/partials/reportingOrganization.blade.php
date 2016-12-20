@if(!emptyOrHasEmptyTemplate($reporting_org))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.reporting_organisation')</div>
            <div class="activity-element-info">
                {!! checkIfEmpty(getFirstNarrative($reporting_org)) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($reporting_org['narrative'])])
                <div class="toggle-btn">
                    <span class="show-more-info">@lang('global.show_more_info')</span>
                    <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>                 
                </div>
                <div class="more-info hidden">
                    <div class="element-info">
                        <div class="activity-element-label pull-left">@lang('elementForm.identifier')</div>
                        <div class="activity-element-info pull-left">{!! checkIfEmpty($reporting_org['reporting_organization_identifier']) !!}</div>
                    </div>
                    <div class="element-info">
                        <div class="activity-element-label pull-left">@lang('elementForm.organisation_type')</div>
                        <div class="activity-element-info pull-left">{!! getCodeNameWithCodeValue('OrganisationType' , $reporting_org['reporting_organization_type'] , -4) !!}</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('settings') }}" class="edit-element">@lang('global.edit')</a>
    </div>
@endif
