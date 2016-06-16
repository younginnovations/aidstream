@if(!empty($defaultTiedStatus))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.default_tied_status')</div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus) , 0 , -4)}}
            </div>
        </div>
        <a href="{{route('activity.default-tied-status.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'default_tied_status'])}}" class="delete pull-right">remove</a>
    </div>
@endif
