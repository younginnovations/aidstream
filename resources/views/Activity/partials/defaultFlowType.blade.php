@if(!empty($defaultFlowType))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.default_flow_type') @if(array_key_exists('Default Flow Type',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('FlowType', $defaultFlowType) , 0 , -4)}}
            </div>
        </div>
        <a href="{{route('activity.default-flow-type.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'default_flow_type'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
