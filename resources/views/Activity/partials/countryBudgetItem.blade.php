@if(!emptyOrHasEmptyTemplate($countryBudgetItems))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.country_budget_items')</div>
            <div class="activity-element-info">
                @foreach($countryBudgetItems[0]['budget_item'] as $budgetItems)
                    <li>{!!  getCountryBudgetItems($countryBudgetItems[0]['vocabulary'], $budgetItems) !!} </li>
                    <div class="toggle-btn">
                        <span class="show-more-info">Show more info</span>
                        <span class="hide-more-info hidden">Hide more info</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.description')</div>
                            <div class="activity-element-info">{!!  getFirstNarrative($budgetItems['description'][0]) !!}</div>
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($budgetItems['description'][0]['narrative'])])
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.vocabulary')</div>
                            <div class="activity-element-info">{!! getCodeNameWithCodeValue('BudgetIdentifierVocabulary' ,$countryBudgetItems[0]['vocabulary'] , -4 ) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{route('activity.country-budget-items.index', $id)}}" class="edit-element">edit</a>
        <a href="{{route('activity.delete-element', [$id, 'country_budget_items'])}}" class="delete pull-right">remove</a>
    </div>
@endif
