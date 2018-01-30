@if(!empty(getVal($activityDataList, ['default_aid_type'], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.default_aid_type') @if(array_key_exists('Default Aid Type',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('AidType', getVal($activityDataList, ['default_aid_type'], [])) , 0 , -5)}}
            </div>
        </div>
        <a href="{{route('activity.default-aid-type.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'default_aid_type', 'id' => $id])
    </div>
@endif
