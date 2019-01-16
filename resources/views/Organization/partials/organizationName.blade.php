@if(!emptyOrHasEmptyTemplate($org_name))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.name')</div>
            <div class="activity-element-info">
                {!! getFirstOrgName($org_name) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($org_name)])
            </div>
        </div>
    </div>
@endif
