@if(!empty($total_budget))
    <div class="panel panel-default">
        <div class="panel-heading">Total Budget
            <a href="{{ route('organization.total-budget.index', $orgId) }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body row panel-level-1">
            @foreach($total_budget as $totalBudget)
                <div class="panel panel-default">
                    <div class="panel-heading">Value</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Text:</div>
                            <div class="col-xs-12 col-xs-8">{{ $totalBudget['value'][0]['amount']}}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Value Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($totalBudget['value'][0]['value_date']) }}</div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Currency:</div>
                            <div class="col-xs-12 col-xs-8">{{ $totalBudget['value'][0]['currency']}}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period Start</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($totalBudget['period_start'][0]['date']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Period End</div>
                    <div class="panel-body panel-element-body row">
                        <div class="col-xs-12 col-md-12">
                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                            <div class="col-xs-12 col-xs-8">{{ formatDate($totalBudget['period_end'][0]['date']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Budget Line</div>
                        <div class="panel-body row">
                            @foreach($totalBudget['budget_line'] as $budgetLine)
                                <div class="panel panel-default">
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Reference:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $budgetLine['reference']}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Value</div>
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Text:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $budgetLine['value'][0]['amount']}}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Value Date:</div>
                                            <div class="col-xs-12 col-xs-8">{{ formatDate($budgetLine['value'][0]['value_date']) }}</div>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Currency:</div>
                                            <div class="col-xs-12 col-xs-8">{{ $budgetLine['value'][0]['currency']}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    @foreach($budgetLine['narrative'] as $budgetLineNarrative)
                                        <div class="panel-heading">Narrative</div>
                                        <div class="panel-body panel-element-body row">
                                            <div class="col-xs-12 col-md-12">
                                                <div class="col-xs-12 col-xs-4">Text:</div>
                                                <div class="col-xs-12 col-xs-8">{{ $budgetLineNarrative['narrative'] . ' [' . $budgetLineNarrative['language'] . ']' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
