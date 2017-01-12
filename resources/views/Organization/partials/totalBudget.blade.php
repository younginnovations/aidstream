@if(!emptyOrHasEmptyTemplate($total_budget))
    <div class="activity-element-wrapper">
        @if(session('version') != 'V201')
            <div class="title">@lang('element.total_budget')</div>
        @endif
        @foreach(groupBudgetElements($total_budget,'status') as  $key => $totalBudgets)
            <div class="activity-element-list">
                @if(session('version') != 'V201')
                    <div class="activity-element-label">{{ $getCode->getCodeNameOnly('BudgetStatus', $key) }}</div>
                @else
                    <div class="activity-element-label">@lang('elementForm.total_budget')</div>
                @endif
                <div class="activity-element-info">
                    @foreach($totalBudgets as $totalBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $totalBudget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>                   
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.period')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period' , $totalBudget)) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.budget_line')</div>
                                <div class="activity-element-info">
                                    @foreach($totalBudget['budget_line'] as $budgetLine)
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                        <div class="expanded">
                                            <div>
                                                <div class="activity-element-label">@lang('elementForm.reference')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty($budgetLine['reference']) !!}</div>
                                            </div>
                                            <div>
                                                <div class="activity-element-label">@lang('elementForm.narrative')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
            <a href="{{ route('organization.total-budget.index', $orgId) }}" class="edit-element">@lang('global.edit')</a>
            <a href="{{ route('organization.delete-element', [$orgId, 'total_budget']) }}" class="delete pull-right">@lang('global.delete')</a>
    </div>
@endif
