@if(!empty($activityScope))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label"> @lang('activityView.activity_scope')</div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('ActivityScope', $activityScope) }}
            </div>
        </div>
        <a href="{{route('activity.activity-scope.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'activity_scope'])}}" class="delete pull-right">remove</a>
    </div>
@endif
