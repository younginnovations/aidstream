@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['related_activity'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.related_activity') @if(array_key_exists('Related Activity',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @foreach(groupActivityElements(getVal($activityDataList, ['related_activity'], []) , 'relationship_type') as $key => $relatedActivities)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">{!! $getCode->getCodeNameOnly('RelatedActivityType' , $key) !!}</div>
                <div class="activity-element-info related-activity">
                    @foreach($relatedActivities as $relatedActivity)
                        <li>{{ getVal($relatedActivity, ['activity_identifier']) }}</li>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.related-activity.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'related_activity', 'id' => $id])
    </div>
@endif
