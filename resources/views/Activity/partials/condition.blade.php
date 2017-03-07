@if(!empty(getVal($activityDataList, ['conditions'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.conditions') @if(array_key_exists('Conditions',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
        @if(getVal($activityDataList, ['conditions', 'condition_attached']) == 0)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">@lang('elementForm.condition_not_attached')</div>
            </div>
        @else
            @foreach(groupActivityElements(getVal($activityDataList, ['conditions', 'condition'],[]),   'condition_type') as $key => $condition)
                <div class="activity-element-list">
                    <div class="activity-element-label">
                        {{ $getCode->getCodeNameOnly('ConditionType',$key) }}
                    </div>
                    <div class="activity-element-info">
                        @foreach($condition as $conditionInfo)
                            <li>
                                {!! getFirstNarrative($conditionInfo) !!}
                                @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($conditionInfo, ['narrative'], []))])
                            </li>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
        <a href="{{route('activity.condition.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'condition'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
