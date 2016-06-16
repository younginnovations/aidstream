@if(!emptyOrHasEmptyTemplate($reporting_org))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.organization_identifier')</div>
            <div class="activity-element-info">
                {{ $reporting_org['reporting_organization_identifier'] }}
            </div>
        </div>
    </div>
@endif