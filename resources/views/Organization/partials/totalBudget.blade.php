@if(!emptyOrHasEmptyTemplate($total_budget))
    <div class="panel panel-default expanded">
        <div class="panel-heading">
            <div class="activity-element-title">Total Budget</div>
            <a href="{{ route('organization.total-budget.index', $orgId) }}" class="edit-element">edit</a>
            <a href="{{ route('organization.delete-element', [$orgId, 'total_budget']) }}" class="delete pull-right">delete</a>
        </div>
        <div class="panel-body row panel-level-2">
            @foreach($total_budget as $totalBudget)
                <div class="panel-heading">
                    <div class="activity-element-title">
                        {{ formatDate($totalBudget['period_start'][0]['date']) }}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-body row">
                            <div class="panel-heading">
                                <div class="activity-element-title">Value</div>
                            </div>
                            <div class="panel-body">
                                <div class="panel panel-default">
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
                            </div>
                            <div class="panel-heading">
                                <div class="activity-element-title">Period Start</div>
                            </div>
                            <div class="panel-body">
                                <div class="panel panel-default">
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                            <div class="col-xs-12 col-xs-8">{{ formatDate($totalBudget['period_start'][0]['date']) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <div class="activity-element-title">Period End</div>
                            </div>
                            <div class="panel-body row">
                                <div class="panel panel-default">
                                    <div class="panel-body panel-element-body row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="col-xs-12 col-xs-4">Iso Date:</div>
                                            <div class="col-xs-12 col-xs-8">{{ formatDate($totalBudget['period_end'][0]['date']) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <div class="activity-element-title">Budget Line</div>
                            </div>
                            <div class="panel-body row">
                                @foreach($totalBudget['budget_line'] as $budgetLine)
                                    <div class="panel-heading">
                                        <div class="activity-element-title">{{ $budgetLine['reference']}}</div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-body panel-element-body row">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="col-xs-12 col-xs-4">Reference:</div>
                                                    <div class="col-xs-12 col-xs-8">{{ $budgetLine['reference']}}</div>
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
                                            <div class="panel-heading">
                                                <div class="activity-element-title">Narrative</div>
                                            </div>
                                            <div class="panel-element-body row">
                                                @foreach($budgetLine['narrative'] as $budgetLineNarrative)
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="col-xs-12 col-xs-4">Text:</div>
                                                        <div class="col-xs-12 col-xs-8">{{ $budgetLineNarrative['narrative'] . hideEmptyArray('Organization', 'Language', $budgetLineNarrative['language']) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
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
