@if(!emptyOrHasEmptyTemplate($recipient_organization_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.recipient_organization_budget')</div>
        @foreach(groupBudgetElements($recipient_organization_budget, 'status') as  $key => $recipientOrganizationBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    @if(session('version') != 'V201')
                        {{ $getCode->getCodeNameOnly('BudgetStatus', $key) }}
                    @else
                        Status Not Available
                    @endif
                </div>
                <div class="activity-element-info">
                    @foreach($recipientOrganizationBudgets as $recipientOrganizationBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $recipientOrganizationBudget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.period')</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period' , $recipientOrganizationBudget)) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.recipient_organization_reference')</div>
                                <div class="activity-element-info">
                                    {!! checkIfEmpty($recipientOrganizationBudget['recipient_organization'][0]['ref']) !!}
                                </div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description')</div>
                                <div class="activity-element-info">
                                    {!! checkIfEmpty(getFirstNarrative($recipientOrganizationBudget['recipient_organization'][0])) !!}
                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($recipientOrganizationBudget['recipient_organization'][0]['narrative'])])
                                </div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.budget_line')</div>
                                @foreach($recipientOrganizationBudget['budget_line'] as $budgetLine)
                                    <div class="activity-element-info">
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                        <div class="expanded">
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('activityView.reference')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty($budgetLine['reference']) !!}</div>
                                            </div>
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('activityView.narrative')</div>
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
           class="edit-element">edit</a>
        <a href="{{ route('organization.delete-element', [$orgId,'recipient_organization_budget'])}}" class="delete pull-right">delete</a>
    </div>
@endif
