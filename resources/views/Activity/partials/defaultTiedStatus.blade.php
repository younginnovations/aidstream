@if(!empty($defaultTiedStatus))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.default_tied_status') @if(array_key_exists('Default Tied Status',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ substr($getCode->getActivityCodeName('TiedStatus', $defaultTiedStatus) , 0 , -4)}}
            </div>
        </div>
        <a href="{{route('activity.default-tied-status.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'default_tied_status'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
