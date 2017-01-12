@if(!empty($capitalSpend))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.capital_spend') @if(array_key_exists('Capital Spend',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                {{ $capitalSpend.'%' }}
            </div>
        </div>
        <a href="{{route('activity.capital-spend.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        <a href="{{route('activity.delete-element', [$id, 'capital_spend'])}}" class="delete pull-right">@lang('global.remove')</a>
    </div>
@endif
