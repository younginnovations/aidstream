@if(!empty(getVal($activityDataList, ['activity_scope'], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.activity_scope') @if(array_key_exists('Activity Scope',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('ActivityScope', getVal($activityDataList, ['activity_scope'], [])) }}
            </div>
        </div>
        <a href="{{route('activity.activity-scope.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'activity_scope', 'id' => $id])
    </div>
@endif
