@if(!emptyOrHasEmptyTemplate(getVal($activityDataList, ['country_budget_items'], [])))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label col-md-4">@lang('element.country_budget_items') @if(array_key_exists('Country Budget Item',$errors)) <i class='imported-from-xml'>icon</i>@endif </div>
            <div class="activity-element-info">
                @foreach(getVal(getVal($activityDataList, ['country_budget_items'], []), [0, 'budget_item'], []) as $budgetItems)
                    <li>{!!  getCountryBudgetItems(getVal(getVal($activityDataList, ['country_budget_items'], []), [0, 'vocabulary']), $budgetItems) !!} </li>
                    <div class="toggle-btn">
                        <span class="show-more-info">@lang('global.show_more_info')</span>
                        <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.description')</div>
                            <div class="activity-element-info">{!!  getFirstNarrative(getVal($budgetItems, ['description', 0], [])) !!}</div>
                            @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages(getVal($budgetItems, ['description', 0, 'narrative'], []))])
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.vocabulary')</div>
                            <div class="activity-element-info">{!! getCodeNameWithCodeValue('BudgetIdentifierVocabulary' ,getVal(getVal($activityDataList, ['country_budget_items'], []), [0, 'vocabulary']) , -4 ) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{route('activity.country-budget-items.index', $id)}}" class="edit-element">@lang('global.edit')</a>
        @include('Activity.partials.element-delete-form', ['element' => 'country_budget_items', 'id' => $id])
    </div>
@endif
