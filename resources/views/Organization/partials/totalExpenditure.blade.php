@if(!emptyOrHasEmptyTemplate($total_expenditure))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Total Expenditure</div>
            <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="edit-element">edit</a>
            <a href="{{ route('organization.delete-element',[$orgId, 'total_expenditure']) }}" class="delete pull-right">delete</a>
        </div>
        <div class="panel-body row panel-level-2">
            @foreach($total_expenditure as $totalExpenditure)
                <div class="panel-heading">
                    <div class="activity-element-title">{{$totalExpenditure['expense_line'][0]['reference']}}</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period Start</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditure['period_start'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Period End</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditure['period_end'][0]['date']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Value</div>
                                </div>
                                <div class="panel-element-body row">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Amount:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $totalExpenditure['value'][0]['amount']}}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Value Date:</div>
                                        <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditure['value'][0]['value_date']) }}</div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-xs-4">Currency:</div>
                                        <div class="col-xs-12 col-xs-8">{{ $totalExpenditure['value'][0]['currency']}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="activity-element-title">Expense Line</div>
                                </div>
                                <div class="panel-drop-body row">
                                    @foreach($totalExpenditure['expense_line'] as $totalExpenditureExpenseLine)
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $totalExpenditureExpenseLine['reference']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Value</div>
                                            </div>
                                            <div class="panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Text:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $totalExpenditureExpenseLine['value'][0]['amount']}}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Value Date:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditureExpenseLine['value'][0]['value_date']) }}</div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Currency:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $totalExpenditureExpenseLine['value'][0]['currency']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Narrative</div>
                                            </div>
                                            <div class="panel-element-body row">
                                                @foreach($totalExpenditureExpenseLine['narrative'] as $totalExpenditureExpenseLineNarrative)
                                                    <div class="panel-body panel-element-body row">
                                                        <div class="col-xs-12 col-md-12">
                                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                                            <div class="col-xs-12 col-xs-8">{{ $totalExpenditureExpenseLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $totalExpenditureExpenseLineNarrative['language']) }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
