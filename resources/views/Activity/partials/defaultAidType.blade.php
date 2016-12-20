@if(!empty($defaultAidType))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.default_aid_type') @if(array_key_exists('Default Aid Type',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('AidType', $defaultAidType) , 0 , -5)}}
            </div>
        </div>
        <a href="{{route('activity.default-aid-type.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'default_aid_type'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
