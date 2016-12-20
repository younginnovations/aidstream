@if(!emptyOrHasEmptyTemplate($total_expenditure))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('element.total_expenditure')</div>
            <div class="activity-element-info">
                @foreach($total_expenditure as $expenditure)
                    <li>{!! getCurrencyValueDate($expenditure['value'][0], "planned") !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">@lang('global.show_more_info')</span>
                        <span class="hide-more-info hidden">@lang('global.hide_more_info')</span>         
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.period')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period',$expenditure)) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('elementForm.expense_line')</div>
                            @foreach($expenditure['expense_line'] as $expense)
                                <div class="activity-element-info">
                                    <li>{!! getCurrencyValueDate($expense['value'][0], 'planned') !!}</li>
                                    <div class="expanded">
                                        <div class="element-info">
                                            <div class="activity-element-label">@lang('elementForm.reference')</div>
                                            <div class="activity-element-info">{!! checkIfEmpty($expense['reference']) !!}</div>
                                        </div>
                                        <div class="element-info">
                                            <div class="activity-element-label">
                                                @lang('elementForm.narrative')</div>
                                            <div class="activity-element-info">
                                                {!! checkIfEmpty(getFirstNarrative($expense)) !!}
                                                @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($expense['narrative'])])
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
        <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="edit-element">@lang('global.edit')</a>
        <a href="{{ route('organization.delete-element',[$orgId, 'total_expenditure']) }}" class="delete pull-right">@lang('global.delete')</a>
    </div>
@endif
