@if(!emptyOrHasEmptyTemplate($org_name))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.name')</div>
            <div class="activity-element-info">
                {!! getFirstOrgName($org_name) !!}
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($org_name)])
            </div>
        </div>
        <a href="{{ url('/organization/' . $orgId . '/name') }}" class="edit-element">edit</a>
        <a href="{{ route('organization.delete-element', [$orgId, 'name']) }}" class="delete pull-right">delete</a>
    </div>
@endif
