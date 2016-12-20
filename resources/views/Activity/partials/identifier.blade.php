@if(!emptyOrHasEmptyTemplate($identifier))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.activity_identifier')</div>
            <div class="activity-element-info">
                {{ $identifier['iati_identifier_text'] }}
            </div>
            <a href="{{route('activity.iati-identifier.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        </div>
    </div>
@endif
