@if(!emptyOrHasEmptyTemplate($recipient_region_budget))
    <div class="activity-element-wrapper">
        <div class="title">@lang('element.recipient_region_budget')</div>
        @foreach(groupBudgetElements($recipient_region_budget, 'status') as  $key => $recipientRegionBudgets)
            <div class="activity-element-list">
                <div class="activity-element-label">
                    {{ $getCode->getCodeNameOnly('BudgetStatus', $key) }}
                </div>
                <div class="activity-element-info">
                    @foreach($recipientRegionBudgets as $recipientRegionBudget)
                        <li>{!! getBudgetInformation('currency_with_valuedate' , $recipientRegionBudget) !!}</li>
                        <div class="toggle-btn">
                            <span class="show-more-info">@lang('global.show_more_info')</span>
                            <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>
                        </div>
                        <div class="more-info hidden">
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.period')</div>
                                <div class="activity-element-info"> {!! checkIfEmpty(getBudgetInformation('period' , $recipientRegionBudget)) !!}</div>
                            </div>
                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.vocabulary')</div>
                                <div class="activity-element-info"> {!! getCodeNameWithCodeValue('RegionVocabulary' , $recipientRegionBudget['recipient_region'][0]['vocabulary'] , -4) !!} </div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.vocabulary_uri')</div>
                                <div class="activity-element-info"> {!! getClickableLink($recipientRegionBudget['recipient_region'][0]['vocabulary_uri']) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.code')</div>
                                <div class="activity-element-info"> {!! getCodeNameWithCodeValue('Region' , $recipientRegionBudget['recipient_region'][0]['code'] , -5) !!}</div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.description')</div>
                                <div class="activity-element-info">
                                    {!! getFirstNarrative($recipientRegionBudget['recipient_region'][0]) !!}<br>
                                    @include('Activity.partials.viewInOtherLanguage', ['otherLanguages' => getOtherLanguages($recipientRegionBudget['recipient_region'][0]['narrative'])])
                                </div>
                            </div>

                            <div class="element-info">
                                <div class="activity-element-label">@lang('elementForm.budget_line')</div>
                                @foreach($recipientRegionBudget['budget_line'] as $budgetLine)
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
        <a href="{{ url('/organization/' . $id . '/recipient-region-budget') }}" class="edit-element">@lang('global.edit')</a>
        <a href="{{ route('organization.delete-element',[$id,'recipient_region_budget']) }}" class="delete pull-right">@lang('global.delete')</a>
    </div>
@endif