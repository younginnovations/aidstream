@if(!empty($activityScope))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.activity_scope') @if(array_key_exists('Activity Scope',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('ActivityScope', $activityScope) }}
            </div>
        </div>
        <a href="{{route('activity.activity-scope.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'activity_scope'])}}" class="delete pull-right">remove</a>
    </div>
@endif
