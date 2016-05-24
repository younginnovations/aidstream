@if(!empty($defaultFlowType))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.default_flow_type')</div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('FlowType', $defaultFlowType) , 0 , -4)}}
            </div>
        </div>
        <a href="{{route('activity.default-flow-type.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'default_flow_type'])}}" class="delete pull-right">remove</a>
    </div>
@endif
