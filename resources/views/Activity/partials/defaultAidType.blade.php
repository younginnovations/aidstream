@if(!empty($defaultAidType))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.default_aid_type')</div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('AidType', $defaultAidType) , 0 , -5)}}
            </div>
        </div>
        <a href="{{route('activity.default-aid-type.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'default_aid_type'])}}" class="delete pull-right">remove</a>
    </div>
@endif
