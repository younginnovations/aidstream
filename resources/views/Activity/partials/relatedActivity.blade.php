@if(!emptyOrHasEmptyTemplate($relatedActivities))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.related_activity')</div>
        @foreach(groupActivityElements($relatedActivities , 'relationship_type') as $key => $relatedActivities)
            <div class="activity-element-list">
                <div class="activity-element-label">{!! $getCode->getCodeNameOnly('RelatedActivityType' , $key) !!}</div>
                <div class="activity-element-info">
                    @foreach($relatedActivities as $relatedActivity)
                        {{ $relatedActivity['activity_identifier'] }}
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.related-activity.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'related-activity'])}}" class="delete pull-right">remove</a>
    </div>
@endif
