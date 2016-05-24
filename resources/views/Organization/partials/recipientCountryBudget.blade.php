@if(!emptyOrHasEmptyTemplate($recipient_country_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('activityView.recipient_country_budget')</div>
        @foreach(groupByCountry($recipient_country_budget) as $key => $countryBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {!! $key !!}
                </div>
                <div class="activity-element-info">
                    @foreach($countryBudgets as $countryBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $countryBudget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">Show more info</span>
                            <span class="hide-more-info hidden">Hide more info</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.period'):</div>
                                <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period' , $countryBudget)) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.description'):</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($countryBudget['recipient_country'][0]) !!} <br>
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($countryBudget['recipient_country'][0]['narrative'])])
                                </div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('activityView.budget_line')</div>
                                @foreach($countryBudget['budget_line'] as $budgetLine)
                                    <div class="activity-element-info">
                                        <li>{!! getCurrencyValueDate($budgetLine['value'][0] , "planned") !!}</li>
                                        <div class="expanded">
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('activityView.reference')</div>
                                                <div class="activity-element-info">{!! checkIfEmpty($budgetLine['reference']) !!}</div>
                                            </div>
                                            <div class="element-info">
                                                <div class="activity-element-label">@lang('activityView.narrative')</div>
                                                <div class="activity-element-info">
                                                    {!! checkIfEmpty(getFirstNarrative($budgetLine)) !!}
                                                    @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($budgetLine['narrative'])])
                                                </div>
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
        <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="edit-element">edit</a>
        <a href="{{ route('organization.delete-element',[$orgId, 'recipient_country_budget']) }}" class="delete pull-right">delete</a>
    </div>
@endif
