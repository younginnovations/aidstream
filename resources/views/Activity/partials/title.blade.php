@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['title'], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.title') @if(array_key_exists('Title',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ getVal($activityDataList, ['title', 0, 'narrative'])}}
                <em>(language: {{ getLanguage(getVal($activityDataList, ['title', 0, 'language'], '')) }})</em>
                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => array_slice(getVal($activityDataList, ['title'], []) , 1)])
            </div>
        </div>
        <a href="{{route('activity.title.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'title', 'id' => $id])
    </div>
@endif
