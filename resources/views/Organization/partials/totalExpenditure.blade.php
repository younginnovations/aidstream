@if(!emptyOrHasEmptyTemplate($total_expenditure))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.total_expenditure')</div>
            <div class="activity-element-info">
                @foreach($total_expenditure as $expenditure)
                    <li>{!! getCurrencyValueDate($expenditure['value'][0], "planned") !!}</li>
                    <div class="toggle-btn">
                        <span class="show-more-info">Show more info</span>
                        <span class="hide-more-info hidden">Hide more info</span>         
                    </div>
                    <div class="more-info hidden">
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.period')</div>
                            <div class="activity-element-info">{!! checkIfEmpty(getBudgetInformation('period',$expenditure)) !!}</div>
                        </div>
                        <div class="element-info">
                            <div class="activity-element-label">@lang('activityView.expense_line')</div>
                            @foreach($expenditure['expense_line'] as $expense)
                                <div class="activity-element-info">
                                    <li>{!! getCurrencyValueDate($expense['value'][0], 'planned') !!}</li>
                                    <div class="expanded">
                                        <div class="element-info">
                                            <div class="activity-element-label">@lang('activityView.reference')</div>
                                            <div class="activity-element-info">{!! checkIfEmpty($expense['reference']) !!}</div>
                                        </div>
                                        <div class="element-info">
                                            <div class="activity-element-label">
                                                @lang('activityView.narrative')</div>
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
        <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="edit-element">edit</a>
        <a href="{{ route('organization.delete-element',[$orgId, 'total_expenditure']) }}" class="delete pull-right">delete</a>
    </div>
@endif
