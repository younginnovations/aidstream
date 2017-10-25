@if(!emptyOrHasEmptyTemplate($organizationData->toArray()))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('elementForm.organisation_identifier')</div>
            <div class="activity-element-info">
                {{ $organizationData->is_reporting_org ? $organizationData->organization_identifier : $organizationData->identifier}}
            </div>
        </div>
    </div>
@endif