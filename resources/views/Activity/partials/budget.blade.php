@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['budget'], [])))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.budget')</div>
        @foreach( groupBudgetElements(getVal($activityDataList, ['budget'], []) , 'budget_type') as $key => $budgets)
            <div class="activity-element-list">
                <div class="activity-element-label col-md-4">{{ $getCode->getCodeNameOnly('BudgetType' , $key) }}</div>
                <div class="activity-element-info">
                    @foreach($budgets as $budget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $budget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.period')</div>
                                <div class="activity-element-info">{!! getBudgetInformation('period' , $budget) !!}</div>
                            </div>
                            @if(session('version') != 'V201')
                                <div class="element-info">
                                    <div class="activity-element-label">@lang('elementForm.status')</div>
                                    <div class="activity-element-info">{!! getBudgetInformation('status' , $budget) !!}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{route('activity.budget.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'budget', 'id' => $id])
    </div>
@endif
