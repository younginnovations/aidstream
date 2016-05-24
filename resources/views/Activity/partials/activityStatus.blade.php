@if(!empty($activityStatus))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.activity_status')</div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('ActivityStatus', $activityStatus) }}
            </div>
        </div>
        <a href="{{route('activity.activity-status.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'activity_status'])}}" class="delete pull-right">remove</a>
    </div>
@endif
