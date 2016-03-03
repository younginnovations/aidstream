@if(!empty($total_expenditure))
    <div class="panel panel-default">
        <div class="panel-heading">Total Expenditure
            <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body row panel-level-1">
            @foreach($total_expenditure as $totalExpenditure)
                <div class="panel panel-default">
                    <div class="panel-body panel-element-body row">
                        <div class="panel panel-default">
                            <div class="panel-heading">Period Start</div>
                            <div class="panel-body panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                    <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditure['period_start'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Period End</div>
                            <div class="panel-body panel-element-body row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                    <div class="col-xs-12 col-xs-8">{{ formatDate($totalExpenditure['period_end'][0]['date']) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Value</div>
                            <div class="panel-body panel-element-body row">
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
                            <div class="panel-heading">Expense Line</div>
                            <div class="panel-body row">
                                @foreach($totalExpenditure['expense_line'] as $totalExpenditureExpenseLine)
                                    <div class="panel panel-default">
                                        <div class="panel-body panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Reference:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $totalExpenditureExpenseLine['reference']}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Value</div>
                                            <div class="panel-body panel-element-body row">
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
                                        <div class="panel-heading">Narrative</div>
                                        <div class="panel panel-default">
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
            @endforeach
        </div>
    </div>
@endif
