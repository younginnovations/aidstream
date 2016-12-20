@if(!empty($activityStatus))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.activity_status') @if(array_key_exists('Activity Status',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('ActivityStatus', $activityStatus) }}
            </div>
        </div>
        <a href="{{route('activity.activity-status.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'activity_status'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
