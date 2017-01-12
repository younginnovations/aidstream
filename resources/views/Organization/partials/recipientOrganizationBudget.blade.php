@if(!emptyOrHasEmptyTemplate($recipient_organization_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.recipient_organisation_budget')</div>
        @foreach(groupBudgetElements($recipient_organization_budget, 'status') as  $key => $recipientOrganizationBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    @if(session('version') != 'V201')
                        {{ $getCode->getCodeNameOnly('BudgetStatus', $key) }}
                    @else
                        @lang('global.status_not_available')
                    @endif
                </div>
                <div class="activity-element-info">
                    @foreach($recipientOrganizationBudgets as $recipientOrganizationBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $recipientOrganizationBudget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.period')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period' , $recipientOrganizationBudget)) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.recipient_organisation_reference')</div>
                                <div class="activity-element-info">
                                    {!! checkIfEmpty($recipientOrganizationBudget['recipient_organization'][0]['ref']) !!}
                                </div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.description')</div>
                                <div class="activity-element-info">
                                    {!! checkIfEmpty(getFirstNarrative($recipientOrganizationBudget['recipient_organization'][0])) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($recipientOrganizationBudget['recipient_organization'][0]['narrative'])])
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.budget_line')</div>
                                @foreach($recipientOrganizationBudget['budget_line'] as $budgetLine)
                                    <div class="activity-element-info">
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                        <div class="expanded">
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('elementForm.reference')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty($budgetLine['reference']) !!}</div>
                                            </div>
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('elementForm.narrative')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <a href="{{ route('organization.recipient-organization-budget.index', $orgId)}}"
           class="edit-element">@lang('global.edit')</a>
        <a href="{{ route('organization.delete-element', [$orgId,'recipient_organization_budget'])}}" class="delete pull-right">@lang('global.delete')</a>
    </div>
@endif
