@if(!emptyOrHasEmptyTemplate($identifier))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.activity_identifier')</div>
            <div class="activity-element-info">
                {{ $identifier['iati_identifier_text'] }}
            </div>
            <a href="{{route('activity.iati-identifier.index', $id)}}" class="edit-element">edit</a>
        </div>
    </div>
@endif
