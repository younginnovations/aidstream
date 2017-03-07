@if(!empty(getVal($activityDataList, ['collaboration_type'], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.collaboration_type') @if(array_key_exists('Collaboration Type',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ $getCode->getCodeNameOnly('CollaborationType' , getVal($activityDataList, ['collaboration_type'], [])) }}
            </div>
        </div>
        <a href="{{route('activity.collaboration-type.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'collaboration_type'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
